<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\MarketBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MarketBookWebController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $books = MarketBook::where('owner_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('marketplace.my-books', compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('frontoffice.add-book');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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

        MarketBook::create($validated);

        return redirect()->route('marketplace.books.index')
            ->with('success', 'Market book created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(MarketBook $book)
    {
        $book->load(['owner', 'transactions.requester', 'transactions.exchangeRequest.offeredMarketBook']);

        return view('marketplace.books.show', compact('book'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MarketBook $book)
    {
        // Check if user owns the book
        if ($book->owner_id !== Auth::id()) {
            return redirect()->route('marketplace.books.index')
                ->with('error', 'You can only edit your own books.');
        }

        return view('marketplace.books.edit', compact('book'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MarketBook $book)
    {
        // Check if user owns the book
        if ($book->owner_id !== Auth::id()) {
            return redirect()->route('marketplace.books.index')
                ->with('error', 'You can only edit your own books.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'condition' => 'required|in:New,Good,Fair,Poor',
            'image' => 'nullable|image|max:2048',
            'price' => 'nullable|numeric|min:0',
            'is_available' => 'sometimes|boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($book->image) {
                Storage::disk('public')->delete($book->image);
            }
            $validated['image'] = $request->file('image')->store('market-books', 'public');
        }

        $book->update($validated);

        return redirect()->route('marketplace.books.show', $book)
            ->with('success', 'Market book updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MarketBook $book)
    {
        // Check if user owns the book
        if ($book->owner_id !== Auth::id()) {
            return redirect()->route('marketplace.books.index')
                ->with('error', 'You can only delete your own books.');
        }

        // Delete image if exists
        if ($book->image) {
            Storage::disk('public')->delete($book->image);
        }

        $book->delete();

        return redirect()->route('marketplace.books.index')
            ->with('success', 'Market book deleted successfully!');
    }

    /**
     * Toggle availability of a market book.
     */
    public function toggleAvailability(MarketBook $book)
    {
        // Check if user owns the book
        if ($book->owner_id !== Auth::id()) {
            return redirect()->back()
                ->with('error', 'You can only toggle availability of your own books.');
        }

        $book->update(['is_available' => !$book->is_available]);

        $status = $book->is_available ? 'available' : 'unavailable';
        return redirect()->back()
            ->with('success', "Book marked as {$status} successfully!");
    }
}