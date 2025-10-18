<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentReaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'comment_id',
        'user_id',
        'reaction_type'
    ];

    // Types de réactions disponibles (identiques aux posts)
    const REACTION_TYPES = [
        'like' => ['emoji' => '👍', 'label' => 'J\'aime', 'color' => '#3B82F6'],
        'love' => ['emoji' => '❤️', 'label' => 'J\'adore', 'color' => '#EF4444'],
        'laugh' => ['emoji' => '😂', 'label' => 'Drôle', 'color' => '#F59E0B'],
        'wow' => ['emoji' => '😮', 'label' => 'Surprenant', 'color' => '#8B5CF6'],
        'sad' => ['emoji' => '😢', 'label' => 'Triste', 'color' => '#6B7280'],
        'angry' => ['emoji' => '😠', 'label' => 'En colère', 'color' => '#DC2626'],
        'celebrate' => ['emoji' => '🎉', 'label' => 'Célébrer', 'color' => '#10B981']
    ];

    /**
     * Relation avec le commentaire
     */
    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtenir les détails d'une réaction
     */
    public function getReactionDetailsAttribute()
    {
        return self::REACTION_TYPES[$this->reaction_type] ?? self::REACTION_TYPES['like'];
    }

    /**
     * Obtenir l'emoji de la réaction
     */
    public function getEmojiAttribute()
    {
        return $this->reaction_details['emoji'];
    }

    /**
     * Obtenir le label de la réaction
     */
    public function getLabelAttribute()
    {
        return $this->reaction_details['label'];
    }

    /**
     * Obtenir la couleur de la réaction
     */
    public function getColorAttribute()
    {
        return $this->reaction_details['color'];
    }

    /**
     * Scope pour filtrer par type de réaction
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('reaction_type', $type);
    }

    /**
     * Scope pour les réactions récentes
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}