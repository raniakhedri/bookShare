<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExchangeRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'offered_marketbook_id',
        'notes',
    ];

    /**
     * Get the transaction for this exchange request.
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * Get the market book being offered in exchange.
     */
    public function offeredMarketBook(): BelongsTo
    {
        return $this->belongsTo(MarketBook::class, 'offered_marketbook_id');
    }

    /**
     * Get the market book being requested (through transaction).
     */
    public function requestedMarketBook(): BelongsTo
    {
        return $this->transaction()->getRelated()->marketBook();
    }

    /**
     * Get the user making the exchange offer (through transaction).
     */
    public function requester(): BelongsTo
    {
        return $this->transaction()->getRelated()->requester();
    }
}
