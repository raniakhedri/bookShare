<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MarketBook extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'description',
        'condition',
        'owner_id',
        'image',
        'price',
        'is_available',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'price' => 'decimal:2',
    ];

    /**
     * Get the owner of the market book.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get all transactions for this market book.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'marketbook_id');
    }

    /**
     * Get all requests received for this market book (alias for transactions).
     */
    public function requestsReceived(): HasMany
    {
        return $this->hasMany(Transaction::class, 'marketbook_id');
    }

    /**
     * Get pending transactions for this market book.
     */
    public function pendingTransactions(): HasMany
    {
        return $this->transactions()->where('status', 'pending');
    }

    /**
     * Get exchange requests where this book is offered.
     */
    public function exchangeOffers(): HasMany
    {
        return $this->hasMany(ExchangeRequest::class, 'offered_marketbook_id');
    }

    /**
     * Scope to get available books only.
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    /**
     * Scope to get books by condition.
     */
    public function scopeByCondition($query, $condition)
    {
        return $query->where('condition', $condition);
    }

    /**
     * Scope to search books by title or author.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('title', 'LIKE', "%{$search}%")
            ->orWhere('author', 'LIKE', "%{$search}%");
    }
}
