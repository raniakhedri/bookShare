<?php
// app/Models/Review.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Events\ReviewCreated;

class Review extends Model
{
    use HasFactory;

    protected $primaryKey = 'review_id';
    
    protected $fillable = [
        'user_id', 'book_id', 'overall_rating', 'content_rating', 
        'condition_rating', 'recommendation_level', 'difficulty_level',
        'review_title', 'review_text', 'reading_context', 'sentiment', 'is_spoiler',
        'content_warnings', 'photo_urls'
    ];

    protected $casts = [
        'overall_rating' => 'decimal:1',
        'content_rating' => 'decimal:1',
        'condition_rating' => 'decimal:1',
        'recommendation_level' => 'decimal:1',
        'difficulty_level' => 'decimal:1',
        'is_spoiler' => 'boolean',
        'photo_urls' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $attributes = [
        'status' => 'active',
        'helpful_votes' => 0,
        'unhelpful_votes' => 0,
        'reply_count' => 0,
        'view_count' => 0,
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function interactions(): HasMany
    {
        return $this->hasMany(ReviewInteraction::class, 'review_id', 'review_id');
    }

    public function replies(): HasMany
    {
        return $this->interactions()->where('interaction_type', 'reply');
    }

    public function votes(): HasMany
    {
        return $this->interactions()->whereIn('interaction_type', ['helpful_vote', 'unhelpful_vote']);
    }

    // Support implicit route model binding when primary key is not `id`
    public function getRouteKeyName(): string
    {
        return $this->getKeyName();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeWithRating($query, $minRating = null)
    {
        if ($minRating) {
            return $query->where('overall_rating', '>=', $minRating);
        }
        return $query->whereNotNull('overall_rating');
    }

    public function scopeMostHelpful($query)
    {
        return $query->orderByDesc('helpful_votes')->orderByDesc('created_at');
    }

    // Accessors
    public function getHelpfulnessRatioAttribute(): float
    {
        $total = $this->helpful_votes + $this->unhelpful_votes;
        return $total > 0 ? round($this->helpful_votes / $total, 2) : 0;
    }

    public function getIsHighQualityAttribute(): bool
    {
        return $this->helpful_votes >= 5 && $this->helpfulness_ratio >= 0.7;
    }

    // Helper Methods
    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }

    public function updateVoteCounts(): void
    {
        $this->helpful_votes = $this->votes()->where('interaction_type', 'helpful_vote')->count();
        $this->unhelpful_votes = $this->votes()->where('interaction_type', 'unhelpful_vote')->count();
        $this->reply_count = $this->replies()->count();
        $this->save();
    }

    public function canBeEditedBy(User $user): bool
    {
        return $this->user_id === $user->id && $this->created_at->diffInHours() < 24;
    }


}