<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'user_id',
        'content',
        'file',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Relation avec les réactions
     */
    public function reactions()
    {
        return $this->hasMany(CommentReaction::class);
    }

    /**
     * Obtenir les réactions groupées par type
     */
    public function getReactionsByTypeAttribute()
    {
        return $this->reactions()
            ->selectRaw('reaction_type, COUNT(*) as count')
            ->groupBy('reaction_type')
            ->pluck('count', 'reaction_type')
            ->toArray();
    }

    /**
     * Obtenir le nombre total de réactions
     */
    public function getTotalReactionsAttribute()
    {
        return $this->reactions()->count();
    }

    /**
     * Vérifier si l'utilisateur connecté a réagi
     */
    public function getUserReactionAttribute()
    {
        if (!auth()->check()) {
            return null;
        }

        return $this->reactions()
            ->where('user_id', auth()->id())
            ->first();
    }
}
