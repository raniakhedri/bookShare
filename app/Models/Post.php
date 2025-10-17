<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'user_id',
        'content',
        'file',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Relation avec les réactions
     */
    public function reactions()
    {
        return $this->hasMany(PostReaction::class);
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

    /**
     * Obtenir les utilisateurs qui ont réagi avec le nombre de réactions
     */
    public function getTopReactorsAttribute()
    {
        return $this->reactions()
            ->with('user')
            ->get()
            ->groupBy('user_id')
            ->map(function ($reactions) {
                return [
                    'user' => $reactions->first()->user,
                    'count' => $reactions->count(),
                    'types' => $reactions->pluck('reaction_type')->toArray()
                ];
            })
            ->sortByDesc('count')
            ->take(5);
    }
}
