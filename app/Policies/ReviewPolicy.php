<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Review;
use App\Models\User;

class ReviewPolicy
{
    /**
     * Determine whether the user can create reviews.
     */
    public function create(User $user): bool
    {
        // Any logged-in user can create a review
        return $user !== null;
    }

    /**
     * Determine whether the user can update the review.
     */
    public function update(User $user, Review $review): bool
    {
        // Only the author of the review can update it
        return $user->id === $review->user_id;
    }

    /**
     * Determine whether the user can delete the review.
     */
    public function delete(User $user, Review $review): bool
    {
        // Only the author can delete, or add admin check if you want
        return $user->id === $review->user_id || $user->role === 'admin';
    }

    /**
     * Determine whether the user can restore the review.
     */
    public function restore(User $user, Review $review): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the review.
     */
    public function forceDelete(User $user, Review $review): bool
    {
        return $user->role === 'admin';
    }
}