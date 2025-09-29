<?php

namespace App\Http\Controllers;

use App\Models\ExchangeRequest;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ExchangeRequestController extends Controller
{
    /**
     * Display a listing of exchange requests.
     */
    public function index(Request $request): JsonResponse
    {
        $query = ExchangeRequest::with([
            'transaction.marketBook.owner',
            'transaction.requester',
            'offeredMarketBook.owner'
        ]);

        // For non-admin users, show only their exchange requests
        if (!Auth::user()->isAdmin()) {
            $query->whereHas('transaction', function ($transactionQuery) {
                $transactionQuery->where('requester_id', Auth::id())
                    ->orWhereHas('marketBook', function ($marketBookQuery) {
                        $marketBookQuery->where('owner_id', Auth::id());
                    });
            });
        }

        $exchangeRequests = $query->orderBy('created_at', 'desc')->paginate(15);

        return response()->json($exchangeRequests);
    }

    /**
     * Display the specified exchange request.
     */
    public function show(ExchangeRequest $exchangeRequest): JsonResponse
    {
        // Check if user has access to this exchange request
        $transaction = $exchangeRequest->transaction;
        if (
            !Auth::user()->isAdmin() &&
            $transaction->requester_id !== Auth::id() &&
            $transaction->marketBook->owner_id !== Auth::id()
        ) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $exchangeRequest->load([
            'transaction.marketBook.owner',
            'transaction.requester',
            'offeredMarketBook.owner'
        ]);

        return response()->json($exchangeRequest);
    }

    /**
     * Update the specified exchange request.
     */
    public function update(Request $request, ExchangeRequest $exchangeRequest): JsonResponse
    {
        // Only the requester can update their exchange request
        if ($exchangeRequest->transaction->requester_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Can only update if transaction is still pending
        if ($exchangeRequest->transaction->status !== 'pending') {
            return response()->json(['message' => 'Cannot update exchange request for non-pending transaction'], 400);
        }

        $validated = $request->validate([
            'offered_marketbook_id' => 'sometimes|exists:market_books,id',
            'notes' => 'nullable|string',
        ]);

        // If changing the offered book, verify ownership and availability
        if (isset($validated['offered_marketbook_id'])) {
            $offeredBook = \App\Models\MarketBook::findOrFail($validated['offered_marketbook_id']);
            if ($offeredBook->owner_id !== Auth::id()) {
                return response()->json(['message' => 'You can only offer books you own'], 400);
            }
            if (!$offeredBook->is_available) {
                return response()->json(['message' => 'Your offered book is not available'], 400);
            }
        }

        $exchangeRequest->update($validated);
        $exchangeRequest->load([
            'transaction.marketBook.owner',
            'transaction.requester',
            'offeredMarketBook.owner'
        ]);

        return response()->json($exchangeRequest);
    }

    /**
     * Remove the specified exchange request from storage.
     * This will also delete the associated transaction.
     */
    public function destroy(ExchangeRequest $exchangeRequest): JsonResponse
    {
        // Only the requester can delete their exchange request
        if ($exchangeRequest->transaction->requester_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Can only delete if transaction is still pending
        if ($exchangeRequest->transaction->status !== 'pending') {
            return response()->json(['message' => 'Cannot delete exchange request for non-pending transaction'], 400);
        }

        // Delete the transaction (which will cascade delete the exchange request)
        $exchangeRequest->transaction->delete();

        return response()->json(['message' => 'Exchange request deleted successfully']);
    }

    /**
     * Get exchange requests where user's books are being offered.
     */
    public function myOfferedBooks(): JsonResponse
    {
        $exchangeRequests = ExchangeRequest::whereHas('offeredMarketBook', function ($query) {
            $query->where('owner_id', Auth::id());
        })
            ->with([
                'transaction.marketBook.owner',
                'transaction.requester',
                'offeredMarketBook'
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json($exchangeRequests);
    }

    /**
     * Get exchange requests made by the user.
     */
    public function myExchangeRequests(): JsonResponse
    {
        $exchangeRequests = ExchangeRequest::whereHas('transaction', function ($query) {
            $query->where('requester_id', Auth::id());
        })
            ->with([
                'transaction.marketBook.owner',
                'offeredMarketBook'
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json($exchangeRequests);
    }
}
