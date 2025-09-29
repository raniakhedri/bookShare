<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'marketbook_id',
        'requester_id',
        'status',
        'type',
        'message',
        'responded_at',
        'response_message',
    ];

    protected $casts = [
        'responded_at' => 'datetime',
    ];

    /**
     * Get the market book for this transaction.
     */
    public function marketBook(): BelongsTo
    {
        return $this->belongsTo(MarketBook::class, 'marketbook_id');
    }

    /**
     * Get the requester (user who made the request).
     */
    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    /**
     * Get the owner of the requested book (through market book).
     */
    public function owner(): BelongsTo
    {
        return $this->marketBook()->getRelated()->owner();
    }

    /**
     * Get the exchange request for this transaction (if it's an exchange).
     */
    public function exchangeRequest(): HasOne
    {
        return $this->hasOne(ExchangeRequest::class);
    }

    /**
     * Scope to get transactions by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get transactions by type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to get pending transactions.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Check if transaction is for a gift.
     */
    public function isGift(): bool
    {
        return $this->type === 'gift';
    }

    /**
     * Check if transaction is for an exchange.
     */
    public function isExchange(): bool
    {
        return $this->type === 'exchange';
    }

    /**
     * Accept the transaction.
     */
    public function accept(?string $message = null): bool
    {
        $this->status = 'accepted';
        $this->responded_at = now();
        if ($message) {
            $this->response_message = $message;
        }
        return $this->save();
    }

    /**
     * Reject the transaction.
     */
    public function reject(?string $message = null): bool
    {
        $this->status = 'rejected';
        $this->responded_at = now();
        if ($message) {
            $this->response_message = $message;
        }
        return $this->save();
    }
}
