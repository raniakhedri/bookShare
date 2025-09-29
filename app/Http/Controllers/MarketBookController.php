<?php

namespace App\Http\Controllers;

use App\Models\MarketBook;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MarketBookController extends Controller
{
    /**
     * Display a listing of the market books.
     */
    public function index(Request $request): JsonResponse
    {
        $query = MarketBook::with(['owner']);

        // Filter by availability
        if ($request->has('available')) {
            $query->available();
        }

        // Filter by condition
        if ($request->filled('condition')) {
            $query->byCondition($request->condition);
        }

        // Search by title or author
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // For non-admin users, exclude their own books from browse
        if (!Auth::user()->isAdmin() && $request->has('exclude_own')) {
            $query->where('owner_id', '!=', Auth::id());
        }

        // For admin, show all books; for users, show only available ones
        if (!Auth::user()->isAdmin()) {
            $query->available();
        }

        $marketBooks = $query->orderBy('created_at', 'desc')->paginate(15);

        return response()->json($marketBooks);
    }

    /**
     * Store a newly created market book in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'condition' => 'required|in:New,Good,Fair,Poor',
            'image' => 'nullable|image|max:2048',
            'price' => 'nullable|numeric|min:0',
        ]);

        $validated['owner_id'] = Auth::id();

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('market-books', 'public');
        }

        $marketBook = MarketBook::create($validated);
        $marketBook->load('owner');

        return response()->json($marketBook, 201);
    }

    /**
     * Display the specified market book.
     */
    public function show(MarketBook $marketBook): JsonResponse
    {
        $marketBook->load(['owner', 'transactions.requester', 'transactions.exchangeRequest.offeredMarketBook']);

        return response()->json($marketBook);
    }

    /**
     * Update the specified market book in storage.
     */
    public function update(Request $request, MarketBook $marketBook): JsonResponse
    {
        // Check if user owns the book or is admin
        if (!Auth::user()->isAdmin() && $marketBook->owner_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'author' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'condition' => 'sometimes|in:New,Good,Fair,Poor',
            'image' => 'nullable|image|max:2048',
            'price' => 'nullable|numeric|min:0',
            'is_available' => 'sometimes|boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($marketBook->image) {
                Storage::disk('public')->delete($marketBook->image);
            }
            $validated['image'] = $request->file('image')->store('market-books', 'public');
        }

        $marketBook->update($validated);
        $marketBook->load('owner');

        return response()->json($marketBook);
    }

    /**
     * Remove the specified market book from storage.
     */
    public function destroy(MarketBook $marketBook): JsonResponse
    {
        // Check if user owns the book or is admin
        if (!Auth::user()->isAdmin() && $marketBook->owner_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Delete image if exists
        if ($marketBook->image) {
            Storage::disk('public')->delete($marketBook->image);
        }

        $marketBook->delete();

        return response()->json(['message' => 'Market book deleted successfully']);
    }

    /**
     * Get market books owned by the authenticated user.
     */
    public function myBooks(): JsonResponse
    {
        $marketBooks = MarketBook::where('owner_id', Auth::id())
            ->with([
                'transactions' => function ($query) {
                    $query->pending();
                }
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json($marketBooks);
    }

    /**
     * Toggle availability of a market book.
     */
    public function toggleAvailability(MarketBook $marketBook): JsonResponse
    {
        // Check if user owns the book
        if ($marketBook->owner_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $marketBook->update(['is_available' => !$marketBook->is_available]);

        return response()->json([
            'message' => 'Availability updated successfully',
            'is_available' => $marketBook->is_available
        ]);
    }
}
