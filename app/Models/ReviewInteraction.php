<?php
// app/Models/ReviewInteraction.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReviewInteraction extends Model
{
    use HasFactory;

    protected $primaryKey = 'interaction_id';
    
    protected $fillable = [
        'review_id', 'user_id', 'interaction_type', 'content',
        'parent_interaction_id', 'sentiment_score', 'quality_score',
        'engagement_weight', 'context_data', 'interaction_depth'
    ];

    protected $casts = [
        'sentiment_score' => 'decimal:2',
        'quality_score' => 'decimal:2',
        'engagement_weight' => 'decimal:2',
        'context_data' => 'array',
        'is_moderated' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

   // Relationships
    public function review(): BelongsTo
    {
        // Use the Review model's primary key dynamically (supports `id` or `review_id`)
        $ownerKey = (new Review())->getKeyName();
        return $this->belongsTo(Review::class, 'review_id', $ownerKey);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parentInteraction(): BelongsTo
    {
        return $this->belongsTo(ReviewInteraction::class, 'parent_interaction_id', 'interaction_id');
    }

    public function childInteractions(): HasMany
    {
        return $this->hasMany(ReviewInteraction::class, 'parent_interaction_id', 'interaction_id');
    }

    public function moderator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'moderator_id');
    }

    // Scopes
    public function scopeReplies($query)
    {
        return $query->where('interaction_type', 'reply');
    }

    public function scopeVotes($query)
    {
        return $query->whereIn('interaction_type', ['helpful_vote', 'unhelpful_vote']);
    }

    public function scopeThreaded($query)
    {
        return $query->orderBy('parent_interaction_id')->orderBy('created_at');
    }

    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_interaction_id');
    }

    // Helper Methods
    public function isVote(): bool
    {
        return in_array($this->interaction_type, ['helpful_vote', 'unhelpful_vote']);
    }

    public function isReply(): bool
    {
        return $this->interaction_type === 'reply';
    }

    public function getThreadDepth(): int
    {
        $depth = 0;
        $current = $this;
        
        while ($current->parent_interaction_id && $depth < 10) { // Prevent infinite loops
            $depth++;
            $current = $current->parentInteraction;
        }
        
        return $depth;
    }

    // Event hooks
    protected static function booted()
    {
        static::created(function ($interaction) {
            // Update review counters when new interaction is created
            $interaction->review->updateVoteCounts();
            
            // Fire event for analytics processing (create this event later)
            // event(new \App\Events\ReviewInteractionAdded($interaction));
        });

        static::deleted(function ($interaction) {
            // Update review counters when interaction is deleted
            $interaction->review->updateVoteCounts();
        });
    }
}