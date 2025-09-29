<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of the reviews.
     */
    public function index()
    {
        $reviews = Review::with(['user', 'book'])->latest()->paginate(10);
        return view('reviews.index', compact('reviews'));
    }

    /**
     * Show the form for creating a new review.
     */
    public function create()
    {
        $book = Book::findOrFail(request()->route('book'));
        return view('reviews.create', compact('book'));
    }

    /**
     * Store a newly created review in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'review_text' => 'required|min:10|max:1000',
            'overall_rating' => 'required|integer|min:1|max:5',
            'book_id' => 'required|exists:books,id'
        ]);

        $review = new Review();
        $review->user_id = auth()->id();
        $review->book_id = $validated['book_id'];
        $review->review_text = $validated['review_text'];
        $review->overall_rating = $validated['overall_rating'];
        $review->status = 'active';
        $review->save();

        return redirect()->route('reviews.show', $review)
            ->with('success', 'Review posted successfully!');
    }

    /**
     * Display the specified review.
     */
    public function show(Review $review)
    {
        $review->load(['user', 'book']);
        return view('reviews.show', compact('review'));
    }

    /**
     * Show the form for editing the specified review.
     */
    public function edit(Review $review)
    {
        $this->authorize('update', $review);
        return view('reviews.edit', compact('review'));
    }

    /**
     * Update the specified review in storage.
     */
    public function update(Request $request, Review $review)
    {
        $this->authorize('update', $review);

        $validated = $request->validate([
            'review_text' => 'required|min:10|max:1000',
            'overall_rating' => 'required|integer|min:1|max:5',
        ]);

        $review->update($validated);

        return redirect()->route('reviews.show', $review)
            ->with('success', 'Review updated successfully!');
    }

    /**
     * Remove the specified review from storage.
     */
    public function destroy(Review $review)
    {
        $this->authorize('delete', $review);
        
        $review->delete();

        return redirect()->route('reviews.index')
            ->with('success', 'Review deleted successfully!');
    }
}