<?php
// app/Http/Controllers/ReviewInteractionController.php

namespace App\Http\Controllers\Frontoffice;

use App\Http\Requests\StoreInteractionRequest;
use App\Models\Review;
use App\Models\ReviewInteraction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ReviewInteractionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a new interaction (vote, reply, report, etc.)
     */
    public function store(StoreInteractionRequest $request, Review $review)
    {
        $interactionType = $request->validated()['interaction_type'];
        
        // Prevent users from interacting with their own reviews (except bookmarks)
        if ($review->user_id === Auth::id() && !in_array($interactionType, ['bookmark'])) {
            return response()->json([
                'error' => 'You cannot interact with your own review'
            ], 403);
        }

        // Check for existing votes to prevent duplicates
        if (in_array($interactionType, ['helpful_vote', 'unhelpful_vote'])) {
            $existingVote = ReviewInteraction::where('review_id', $review->review_id)
                                           ->where('user_id', Auth::id())
                                           ->whereIn('interaction_type', ['helpful_vote', 'unhelpful_vote'])
                                           ->first();

            if ($existingVote) {
                // If same vote type, remove it (toggle)
                if ($existingVote->interaction_type === $interactionType) {
                    $existingVote->delete();
                    return response()->json([
                        'message' => 'Vote removed',
                        'action' => 'removed'
                    ]);
                } else {
                    // If different vote type, update it
                    $existingVote->update(['interaction_type' => $interactionType]);
                    return response()->json([
                        'message' => 'Vote updated',
                        'action' => 'updated'
                    ]);
                }
            }
        }

        // Create the interaction
        $interactionData = $request->validated();
        $interactionData['user_id'] = Auth::id();
        $interactionData['review_id'] = $review->review_id;

        // Calculate interaction depth for threaded replies
        if ($interactionData['parent_interaction_id']) {
            $parentInteraction = ReviewInteraction::find($interactionData['parent_interaction_id']);
            $interactionData['interaction_depth'] = $parentInteraction ? $parentInteraction->interaction_depth + 1 : 0;
        }

        $interaction = ReviewInteraction::create($interactionData);

        // Load the interaction with user data
        $interaction->load('user:id,name');

        return response()->json([
            'message' => 'Interaction added successfully',
            'interaction' => $interaction,
            'action' => 'created'
        ], 201);
    }

    /**
     * Update an interaction (mainly for editing replies)
     */
    public function update(StoreInteractionRequest $request, ReviewInteraction $interaction)
    {
        // Only allow users to edit their own interactions
        if ($interaction->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Only allow editing of replies and within time limit
        if ($interaction->interaction_type !== 'reply' || 
            $interaction->created_at->diffInHours() > 24) {
            return response()->json(['error' => 'Cannot edit this interaction'], 403);
        }

        $interaction->update($request->validated());

        return response()->json([
            'message' => 'Interaction updated successfully',
            'interaction' => $interaction
        ]);
    }

    /**
     * Delete an interaction
     */
    public function destroy(ReviewInteraction $interaction)
    {
        // Only allow users to delete their own interactions or admins
        if ($interaction->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $interaction->delete();

        return response()->json(['message' => 'Interaction deleted successfully']);
    }

    /**
     * Get threaded discussions for a review
     */
    public function discussions(Review $review)
    {
        $discussions = ReviewInteraction::with(['user:id,name', 'childInteractions.user:id,name'])
                                      ->where('review_id', $review->review_id)
                                      ->where('interaction_type', 'reply')
                                      ->whereNull('parent_interaction_id') // Top-level replies only
                                      ->orderBy('created_at')
                                      ->get();

        return response()->json($discussions);
    }

    /**
     * Get user's own interactions
     */
    public function myInteractions()
    {
        $interactions = ReviewInteraction::with(['review.book:id,title', 'review.user:id,name'])
                                        ->where('user_id', Auth::id())
                                        ->latest()
                                        ->paginate(20);

        return response()->json($interactions);
    }

    /**
     * Get voting statistics for a review
     */
    public function voteStats(Review $review)
    {
        $stats = ReviewInteraction::where('review_id', $review->review_id)
                                ->whereIn('interaction_type', ['helpful_vote', 'unhelpful_vote'])
                                ->select('interaction_type', DB::raw('count(*) as count'))
                                ->groupBy('interaction_type')
                                ->get()
                                ->pluck('count', 'interaction_type');

        return response()->json([
            'helpful_votes' => $stats['helpful_vote'] ?? 0,
            'unhelpful_votes' => $stats['unhelpful_vote'] ?? 0,
            'total_votes' => ($stats['helpful_vote'] ?? 0) + ($stats['unhelpful_vote'] ?? 0),
            'helpfulness_ratio' => $review->helpfulness_ratio
        ]);
    }

    /**
     * Report an interaction or review
     */
    public function report(Request $request, ReviewInteraction $interaction)
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        // Check if user already reported this
        $existingReport = ReviewInteraction::where('review_id', $interaction->review_id)
                                         ->where('user_id', Auth::id())
                                         ->where('interaction_type', 'report')
                                         ->where('parent_interaction_id', $interaction->interaction_id)
                                         ->first();

        if ($existingReport) {
            return response()->json(['error' => 'You have already reported this content'], 409);
        }

        ReviewInteraction::create([
            'review_id' => $interaction->review_id,
            'user_id' => Auth::id(),
            'interaction_type' => 'report',
            'content' => $request->reason,
            'parent_interaction_id' => $interaction->interaction_id,
            'interaction_depth' => 0
        ]);

        return response()->json(['message' => 'Report submitted successfully']);
    }

    /**
     * Get bookmarked reviews for the authenticated user
     */
    public function bookmarks()
    {
        $bookmarks = ReviewInteraction::with(['review.book:id,title,author', 'review.user:id,name'])
                                    ->where('user_id', Auth::id())
                                    ->where('interaction_type', 'bookmark')
                                    ->latest()
                                    ->paginate(10);

        return response()->json($bookmarks);
    }
}