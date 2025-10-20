<?php

namespace App\Http\Controllers\Frontoffice;

use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Models\Book;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Services\HuggingFaceSentimentService;
use Illuminate\Support\Facades\Http;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of reviews for a specific book (or all reviews if no book).
     * Note: $book is optional — route can call /books/{book}/reviews or /reviews (if you have that).
     */
    public function index(Request $request, Book $book = null)
    {
        $query = Review::with([
            'user:id,name',
            'book:id,title,author,image',
            'interactions' => function($q) {
                $q->where('interaction_type', 'reply')->latest()->limit(3);
            }
        ])->active();

        // Filter by book if provided
        if ($book) {
            $query->where('book_id', $book->id);
        }

        // Apply filters
        if ($request->has('min_rating')) {
            $query->withRating($request->get('min_rating'));
        }

        if ($request->has('user_id')) {
            $query->where('user_id', $request->get('user_id'));
        }

        // Apply sorting
        switch ($request->get('sort', 'helpful')) {
            case 'recent':
                $query->latest();
                break;
            case 'rating_high':
                $query->orderByDesc('overall_rating');
                break;
            case 'rating_low':
                $query->orderBy('overall_rating');
                break;
            case 'helpful':
            default:
                $query->mostHelpful();
                break;
        }

        $reviews = $query->paginate(10);

        // If API request, return JSON
        if ($request->expectsJson()) {
            return response()->json($reviews);
        }

        // Use consistent view namespace for frontoffice
        return view('frontoffice.reviews.index', [
            'reviews' => $reviews,
            'book' => $book,
        ]);
    }

    /**
     * Show the form for creating a new review
     */
public function create($bookId)
{
    $book = Book::findOrFail($bookId);

    // Check if user already reviewed this book
    $existingReview = Review::where('user_id', Auth::id())
                           ->where('book_id', $bookId)
                           ->first();

    if ($existingReview) {
        // Use review_id (your actual primary key) instead of id
        return redirect()->route('reviews.edit', $existingReview->review_id)
                         ->with('info', 'You already have a review for this book. You can edit it here.');
    }

    return view('frontoffice.reviews.create', compact('book'));
}

    /**
     * Store a newly created review
     */
public function store(StoreReviewRequest $request, $bookId)
{
    $book = Book::findOrFail($bookId);

    // Prevent duplicate reviews
    $existingReview = Review::where('user_id', Auth::id())
        ->where('book_id', $bookId)
        ->first();

    if ($existingReview) {
        return redirect()->back()
            ->with('error', 'You already have a review for this book.');
    }

    $reviewData = $request->validated();
    $reviewData['user_id'] = Auth::id();
    $reviewData['book_id'] = $bookId;

    // Upload photos if present
    if ($request->hasFile('photos')) {
        $photoUrls = [];
        foreach ($request->file('photos') as $photo) {
            $photoUrls[] = $photo->store('review-photos', 'public');
        }
        $reviewData['photo_urls'] = $photoUrls;
    }

    // ✅ Analyze sentiment before creating the review
    $hf = new HuggingFaceSentimentService();
    $reviewData['sentiment'] = $hf->analyze($reviewData['review_text'] ?? '');

    $review = Review::create($reviewData);

    return redirect($request->input('redirect_to', route('books.show', $bookId)))
        ->with('success', 'Your review has been published successfully!');
}

    /**
     * Display the specified review
     */
    public function show(Review $review)
    {
        if (method_exists($review, 'incrementViewCount')) {
            $review->incrementViewCount();
        }

        $review->load([
            'user:id,name',
            'book:id,title,author',
            'interactions.user:id,name',
            'interactions' => function($q) {
                $q->threaded();
            }
        ]);

        if (request()->expectsJson()) {
            return response()->json($review);
        }

        return view('frontoffice.reviews.show', compact('review'));
    }

    /**
     * Show the form for editing the review
     */
    public function edit(Review $review)
    {
        $this->authorize('update', $review);

        return view('frontoffice.reviews.edit', compact('review'));
    }

    /**
     * Update the specified review
     */
public function update(UpdateReviewRequest $request, Review $review)
{
    $this->authorize('update', $review);

    $reviewData = $request->validated();

    // Upload new photos
    if ($request->hasFile('photos')) {
        if (!empty($review->photo_urls)) {
            foreach ($review->photo_urls as $oldPhoto) {
                Storage::disk('public')->delete($oldPhoto);
            }
        }

        $photoUrls = [];
        foreach ($request->file('photos') as $photo) {
            $photoUrls[] = $photo->store('review-photos', 'public');
        }
        $reviewData['photo_urls'] = $photoUrls;
    }

    // ✅ Reanalyze sentiment on update
    $hf = new HuggingFaceSentimentService();
    $reviewData['sentiment'] = $hf->analyze($reviewData['review_text'] ?? '');

    $review->update($reviewData);
    Cache::forget("book_reviews_{$review->book_id}");

    return redirect()->route('reviews.show', $review)
        ->with('success', 'Your review has been updated!');
}
    /**
     * Remove the specified review
     */
    public function destroy(Review $review)
    {
        $this->authorize('delete', $review);

        // Delete associated photos
        if (!empty($review->photo_urls)) {
            foreach ($review->photo_urls as $photo) {
                Storage::disk('public')->delete($photo);
            }
        }

        $bookId = $review->book_id;
        $review->delete();

        // Clear cache
        Cache::forget("book_reviews_{$bookId}");

        if (request()->expectsJson()) {
            return response()->json(['message' => 'Review deleted successfully']);
        }

        // Redirect back to the book's reviews listing
        return redirect()->route('reviews.index', $bookId)
                         ->with('success', 'Review deleted successfully');
    }

    /**
     * Get reviews for a specific book (API endpoint)
     */
    public function bookReviews($bookId)
    {
        $reviews = Cache::remember("book_reviews_{$bookId}", 600, function () use ($bookId) {
            return Review::with(['user:id,name'])
                         ->where('book_id', $bookId)
                         ->active()
                         ->mostHelpful()
                         ->get();
        });

        return response()->json($reviews);
    }

    /**
     * Get user's own reviews
     */
    public function myReviews()
    {
        $reviews = Review::with(['book:id,title,author'])
                        ->where('user_id', Auth::id())
                        ->latest()
                        ->paginate(10);

        if (request()->expectsJson()) {
            return response()->json($reviews);
        }

        return view('frontoffice.reviews.my-reviews', compact('reviews'));
    }

}
