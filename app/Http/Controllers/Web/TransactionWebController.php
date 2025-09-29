<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\MarketBook;
use App\Models\Transaction;
use App\Models\ExchangeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionWebController extends Controller
{
    /**
     * Show the form for creating a new transaction.
     */
    public function create(MarketBook $book)
    {
        // Check if the book is available
        if (!$book->is_available) {
            return redirect()->route('marketplace.browse')
                ->with('error', 'This book is not available for request.');
        }

        // Check if user is not requesting their own book
        if ($book->owner_id === Auth::id()) {
            return redirect()->route('marketplace.browse')
                ->with('error', 'You cannot request your own book.');
        }

        // Get user's available books for potential exchange
        $userBooks = MarketBook::where('owner_id', Auth::id())
            ->where('is_available', true)
            ->get();

        return view('frontoffice.request-book', compact('book', 'userBooks'));
    }

    /**
     * Store a newly created transaction.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'market_book_id' => 'required|exists:market_books,id',
            'type' => 'required|in:gift,exchange',
            'message' => 'required|string|max:1000',
            'exchange_book_id' => 'required_if:type,exchange|exists:market_books,id',
        ]);

        // Check if the market book is available
        $marketBook = MarketBook::findOrFail($validated['market_book_id']);
        if (!$marketBook->is_available) {
            return redirect()->back()
                ->with('error', 'This book is not available for request.');
        }

        // Check if user is not requesting their own book
        if ($marketBook->owner_id === Auth::id()) {
            return redirect()->back()
                ->with('error', 'You cannot request your own book.');
        }

        // For exchange, check if offered book belongs to requester
        if ($validated['type'] === 'exchange') {
            $offeredBook = MarketBook::findOrFail($validated['exchange_book_id']);
            if ($offeredBook->owner_id !== Auth::id()) {
                return redirect()->back()
                    ->with('error', 'You can only offer books you own.');
            }
            if (!$offeredBook->is_available) {
                return redirect()->back()
                    ->with('error', 'Your offered book is not available.');
            }
        }

        DB::beginTransaction();
        try {
            // Create transaction
            $transaction = Transaction::create([
                'marketbook_id' => $validated['market_book_id'],
                'requester_id' => Auth::id(),
                'type' => $validated['type'],
                'message' => $validated['message'],
            ]);

            // Create exchange request if it's an exchange
            if ($validated['type'] === 'exchange') {
                ExchangeRequest::create([
                    'transaction_id' => $transaction->id,
                    'offered_marketbook_id' => $validated['exchange_book_id'],
                    'notes' => null,
                ]);
            }

            DB::commit();

            $message = $validated['type'] === 'gift' ?
                'Gift request sent successfully!' :
                'Exchange request sent successfully!';

            return redirect()->route('marketplace.my-requests')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Failed to create request. Please try again.');
        }
    }

    /**
     * Display the specified transaction.
     */
    public function show(Transaction $transaction)
    {
        // Check if user has access to this transaction
        if (
            $transaction->requester_id !== Auth::id() &&
            $transaction->marketBook->owner_id !== Auth::id()
        ) {
            return redirect()->route('marketplace')
                ->with('error', 'You do not have access to this transaction.');
        }

        $transaction->load(['marketBook.owner', 'requester', 'exchangeRequest.offeredMarketBook.owner']);

        return view('marketplace.transactions.show', compact('transaction'));
    }

    /**
     * Respond to a transaction (accept/reject).
     */
    public function respond(Request $request, Transaction $transaction)
    {
        // Only book owner can respond to transaction
        if ($transaction->marketBook->owner_id !== Auth::id()) {
            return redirect()->back()
                ->with('error', 'You can only respond to requests for your own books.');
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

                $message = 'Request accepted successfully!';
            } else {
                $transaction->reject($validated['response_message'] ?? null);
                $message = 'Request rejected.';
            }

            DB::commit();

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Failed to respond to request. Please try again.');
        }
    }

    /**
     * Mark transaction as completed.
     */
    public function complete(Transaction $transaction)
    {
        // Either the book owner or requester can mark as completed
        if (
            $transaction->marketBook->owner_id !== Auth::id() &&
            $transaction->requester_id !== Auth::id()
        ) {
            return redirect()->back()
                ->with('error', 'You can only complete your own transactions.');
        }

        if ($transaction->status !== 'accepted') {
            return redirect()->back()
                ->with('error', 'Only accepted transactions can be marked as completed.');
        }

        $transaction->update(['status' => 'completed']);

        return redirect()->back()
            ->with('success', 'Transaction marked as completed!');
    }
}