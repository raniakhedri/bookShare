<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\MarketBook;
use App\Models\ExchangeRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Display a listing of transactions.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Transaction::with(['marketBook.owner', 'requester', 'exchangeRequest.offeredMarketBook']);

        // Filter by status
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->byType($request->type);
        }

        // For non-admin users, show only their transactions
        if (!Auth::user()->isAdmin()) {
            $query->where(function ($q) {
                $q->where('requester_id', Auth::id())
                    ->orWhereHas('marketBook', function ($marketQuery) {
                        $marketQuery->where('owner_id', Auth::id());
                    });
            });
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(15);

        return response()->json($transactions);
    }

    /**
     * Store a newly created transaction in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'marketbook_id' => 'required|exists:market_books,id',
            'type' => 'required|in:gift,exchange',
            'message' => 'nullable|string',
            'offered_marketbook_id' => 'required_if:type,exchange|exists:market_books,id',
            'exchange_notes' => 'nullable|string',
        ]);

        // Check if the market book is available
        $marketBook = MarketBook::findOrFail($validated['marketbook_id']);
        if (!$marketBook->is_available) {
            return response()->json(['message' => 'This book is not available'], 400);
        }

        // Check if user is not requesting their own book
        if ($marketBook->owner_id === Auth::id()) {
            return response()->json(['message' => 'You cannot request your own book'], 400);
        }

        // For exchange, check if offered book belongs to requester
        if ($validated['type'] === 'exchange') {
            $offeredBook = MarketBook::findOrFail($validated['offered_marketbook_id']);
            if ($offeredBook->owner_id !== Auth::id()) {
                return response()->json(['message' => 'You can only offer books you own'], 400);
            }
            if (!$offeredBook->is_available) {
                return response()->json(['message' => 'Your offered book is not available'], 400);
            }
        }

        DB::beginTransaction();
        try {
            // Create transaction
            $transaction = Transaction::create([
                'marketbook_id' => $validated['marketbook_id'],
                'requester_id' => Auth::id(),
                'type' => $validated['type'],
                'message' => $validated['message'] ?? null,
            ]);

            // Create exchange request if it's an exchange
            if ($validated['type'] === 'exchange') {
                ExchangeRequest::create([
                    'transaction_id' => $transaction->id,
                    'offered_marketbook_id' => $validated['offered_marketbook_id'],
                    'notes' => $validated['exchange_notes'] ?? null,
                ]);
            }

            DB::commit();

            $transaction->load(['marketBook.owner', 'requester', 'exchangeRequest.offeredMarketBook']);
            return response()->json($transaction, 201);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Failed to create transaction'], 500);
        }
    }

    /**
     * Display the specified transaction.
     */
    public function show(Transaction $transaction): JsonResponse
    {
        // Check if user has access to this transaction
        if (
            !Auth::user()->isAdmin() &&
            $transaction->requester_id !== Auth::id() &&
            $transaction->marketBook->owner_id !== Auth::id()
        ) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $transaction->load(['marketBook.owner', 'requester', 'exchangeRequest.offeredMarketBook']);
        return response()->json($transaction);
    }

    /**
     * Update the specified transaction (accept/reject).
     */
    public function update(Request $request, Transaction $transaction): JsonResponse
    {
        // Only book owner can update transaction status
        if ($transaction->marketBook->owner_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'status' => 'required|in:accepted,rejected',
            'response_message' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            if ($validated['status'] === 'accepted') {
                $transaction->accept($validated['response_message'] ?? null);

                // Mark book as unavailable when accepted
                $transaction->marketBook->update(['is_available' => false]);

                // If it's an exchange, also mark the offered book as unavailable
                if ($transaction->isExchange() && $transaction->exchangeRequest) {
                    $transaction->exchangeRequest->offeredMarketBook->update(['is_available' => false]);
                }
            } else {
                $transaction->reject($validated['response_message'] ?? null);
            }

            DB::commit();

            $transaction->load(['marketBook.owner', 'requester', 'exchangeRequest.offeredMarketBook']);
            return response()->json($transaction);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Failed to update transaction'], 500);
        }
    }

    /**
     * Get transactions where user is the requester.
     */
    public function myRequests(): JsonResponse
    {
        $transactions = Transaction::where('requester_id', Auth::id())
            ->with(['marketBook.owner', 'exchangeRequest.offeredMarketBook'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json($transactions);
    }

    /**
     * Get transactions for books owned by the user.
     */
    public function requestsForMyBooks(): JsonResponse
    {
        $transactions = Transaction::whereHas('marketBook', function ($query) {
            $query->where('owner_id', Auth::id());
        })
            ->with(['requester', 'marketBook', 'exchangeRequest.offeredMarketBook'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json($transactions);
    }

    /**
     * Mark transaction as completed.
     */
    public function markCompleted(Transaction $transaction): JsonResponse
    {
        // Either the book owner or requester can mark as completed
        if (
            $transaction->marketBook->owner_id !== Auth::id() &&
            $transaction->requester_id !== Auth::id()
        ) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($transaction->status !== 'accepted') {
            return response()->json(['message' => 'Only accepted transactions can be marked as completed'], 400);
        }

        $transaction->update(['status' => 'completed']);

        return response()->json([
            'message' => 'Transaction marked as completed',
            'transaction' => $transaction
        ]);
    }
}
