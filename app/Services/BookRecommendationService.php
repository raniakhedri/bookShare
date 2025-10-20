<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class BookRecommendationService
{
    /**
     * Recommend books for the current user based on review sentiment and user history.
     *
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */
    public function recommend(int $limit = 5)
    {
        $user = Auth::user();
        if (!$user) {
            // For guests, recommend top-rated books
            return Book::with('category')
                ->whereHas('reviews', function($q) {
                    $q->where('sentiment', 'positive');
                })
                ->withCount(['reviews as positive_reviews_count' => function($q) {
                    $q->where('sentiment', 'positive');
                }])
                ->orderByDesc('positive_reviews_count')
                ->take($limit)
                ->get();
        }

        // Get books the user has reviewed positively
        $likedBookIds = Review::where('user_id', $user->id)
            ->where('sentiment', 'positive')
            ->pluck('book_id')
            ->toArray();

        // Recommend books in the same categories as liked books, not already reviewed
        $categoryIds = Book::whereIn('id', $likedBookIds)->pluck('category_id')->unique();

        $recommendations = Book::with('category')
            ->whereIn('category_id', $categoryIds)
            ->whereNotIn('id', $likedBookIds)
            ->withCount(['reviews as positive_reviews_count' => function($q) {
                $q->where('sentiment', 'positive');
            }])
            ->orderByDesc('positive_reviews_count')
            ->take($limit)
            ->get();

        // Fallback: If not enough, fill with top-rated books
        if ($recommendations->count() < $limit) {
            $more = Book::with('category')
                ->whereNotIn('id', array_merge($likedBookIds, $recommendations->pluck('id')->toArray()))
                ->withCount(['reviews as positive_reviews_count' => function($q) {
                    $q->where('sentiment', 'positive');
                }])
                ->orderByDesc('positive_reviews_count')
                ->take($limit - $recommendations->count())
                ->get();
            $recommendations = $recommendations->concat($more);
        }

        return $recommendations;
    }
}
