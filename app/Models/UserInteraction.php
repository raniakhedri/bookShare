<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UserInteraction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'interaction_type',
        'interaction_value',
        'duration_seconds',
        'context_data',
        'timestamp'
    ];

    protected $casts = [
        'context_data' => 'array',
        'timestamp' => 'datetime',
        'interaction_value' => 'decimal:2'
    ];

    // Types d'interaction possibles
    const INTERACTION_TYPES = [
        'VIEW' => 'view',           // Consulter un livre
        'LIKE' => 'like',           // Aimer un livre
        'SHARE' => 'share',         // Partager un livre
        'DOWNLOAD' => 'download',   // Télécharger un livre
        'READ_TIME' => 'read_time', // Temps de lecture
        'SEARCH' => 'search',       // Recherche effectuée
        'RATE' => 'rate',          // Noter un livre
        'COMMENT' => 'comment',     // Commenter
        'BOOKMARK' => 'bookmark',   // Marque-page
        'WISHLIST' => 'wishlist'    // Liste de souhaits
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec le livre
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Enregistrer une interaction utilisateur
     */
    public static function recordInteraction($userId, $bookId, $type, $value = 1.0, $duration = null, $context = [])
    {
        return static::create([
            'user_id' => $userId,
            'book_id' => $bookId,
            'interaction_type' => $type,
            'interaction_value' => $value,
            'duration_seconds' => $duration,
            'context_data' => $context,
            'timestamp' => Carbon::now()
        ]);
    }

    /**
     * Obtenir les interactions récentes d'un utilisateur
     */
    public static function getRecentInteractions($userId, $limit = 100)
    {
        return static::where('user_id', $userId)
            ->orderBy('timestamp', 'desc')
            ->limit($limit)
            ->with('book')
            ->get();
    }

    /**
     * Calculer le score d'intérêt pour un type d'interaction
     */
    public static function getInteractionWeight($type)
    {
        $weights = [
            'VIEW' => 1.0,
            'LIKE' => 3.0,
            'SHARE' => 5.0,
            'DOWNLOAD' => 7.0,
            'READ_TIME' => 2.0,
            'SEARCH' => 0.5,
            'RATE' => 4.0,
            'COMMENT' => 6.0,
            'BOOKMARK' => 8.0,
            'WISHLIST' => 9.0
        ];

        return $weights[$type] ?? 1.0;
    }

    /**
     * Obtenir les livres les plus populaires
     */
    public static function getMostPopularBooks($limit = 10, $days = 30)
    {
        return static::where('timestamp', '>=', Carbon::now()->subDays($days))
            ->selectRaw('book_id, COUNT(*) as interaction_count, SUM(interaction_value) as total_score')
            ->groupBy('book_id')
            ->orderBy('total_score', 'desc')
            ->limit($limit)
            ->with('book')
            ->get();
    }
}