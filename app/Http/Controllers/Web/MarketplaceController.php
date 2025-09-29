<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\MarketBook;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarketplaceController extends Controller
{
    /**
     * Display the main marketplace dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        // Get marketplace data for the frontoffice template
        $totalBooks = MarketBook::where('is_available', true)->count();
        $userBooks = $user ? MarketBook::where('owner_id', $user->id)->count() : 0;
        $recentBooks = MarketBook::with('owner')
            ->where('is_available', true)
            ->latest()
            ->take(8)
            ->get();

        return view('frontoffice.marketplace', compact('totalBooks', 'userBooks', 'recentBooks'));
    }

    /**
     * Display books for browsing with search and filters.
     */
    public function browse(Request $request)
    {
        $query = MarketBook::with('owner')
            ->where('is_available', true);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by condition
        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        $books = $query->latest()->paginate(12);

        return view('frontoffice.browse-books', compact('books'));
    }

    /**
     * Display user's own books.
     */
    public function myBooks()
    {
        $user = Auth::user();

        $books = MarketBook::where('owner_id', $user->id)
            ->withCount([
                'transactions as pending_requests_count' => function ($query) {
                    $query->where('status', 'pending');
                }
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('marketplace.my-books', compact('books'));
    }

    /**
     * Display user's requests.
     */
    public function myRequests()
    {
        $user = Auth::user();

        $transactions = Transaction::where('requester_id', $user->id)
            ->with(['marketBook.owner', 'exchangeRequest.offeredMarketBook'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('marketplace.my-requests', compact('transactions'));
    }

    /**
     * Display requests received for user's books.
     */
    public function receivedRequests()
    {
        $user = Auth::user();

        $transactions = Transaction::whereHas('marketBook', function ($query) use ($user) {
            $query->where('owner_id', $user->id);
        })->with(['requester', 'marketBook', 'exchangeRequest.offeredMarketBook'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('marketplace.received-requests', compact('transactions'));
    }
}
