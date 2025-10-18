<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AISearchInteraction extends Model
{
    protected $fillable = [
        'user_id',
        'search_description',
        'analysis_result',
        'recommendations',
        'recommendations_count',
        'satisfaction_score',
        'user_feedback',
        'session_id',
        'search_timestamp'
    ];

    protected $casts = [
        'analysis_result' => 'array',
        'recommendations' => 'array',
        'search_timestamp' => 'datetime',
        'satisfaction_score' => 'decimal:2'
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Enregistrer une nouvelle interaction de recherche
     */
    public static function recordSearch($userId, $description, $analysis, $recommendations, $sessionId = null)
    {
        return static::create([
            'user_id' => $userId,
            'search_description' => $description,
            'analysis_result' => $analysis,
            'recommendations' => $recommendations,
            'recommendations_count' => count($recommendations),
            'session_id' => $sessionId ?: uniqid('search_'),
            'search_timestamp' => now()
        ]);
    }

    /**
     * Mettre à jour le feedback utilisateur
     */
    public function updateFeedback($satisfactionScore, $feedback = null)
    {
        $this->update([
            'satisfaction_score' => $satisfactionScore,
            'user_feedback' => $feedback
        ]);
    }

    /**
     * Obtenir les statistiques de recherche pour un utilisateur
     */
    public static function getUserSearchStats($userId, $days = 30)
    {
        return static::where('user_id', $userId)
            ->where('search_timestamp', '>=', now()->subDays($days))
            ->selectRaw('
                COUNT(*) as total_searches,
                AVG(recommendations_count) as avg_recommendations,
                AVG(satisfaction_score) as avg_satisfaction,
                COUNT(DISTINCT session_id) as unique_sessions
            ')
            ->first();
    }

    /**
     * Obtenir les termes de recherche les plus populaires
     */
    public static function getPopularSearchTerms($limit = 10, $days = 30)
    {
        return static::where('search_timestamp', '>=', now()->subDays($days))
            ->selectRaw('search_description, COUNT(*) as search_count')
            ->groupBy('search_description')
            ->orderBy('search_count', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Obtenir les analyses les plus communes
     */
    public static function getCommonAnalysisPatterns($days = 30)
    {
        $interactions = static::where('search_timestamp', '>=', now()->subDays($days))
            ->whereNotNull('analysis_result')
            ->get();

        $genreCounts = [];
        $moodCounts = [];

        foreach ($interactions as $interaction) {
            $analysis = $interaction->analysis_result;
            
            if (isset($analysis['primary_genre'])) {
                $genre = $analysis['primary_genre'];
                $genreCounts[$genre] = ($genreCounts[$genre] ?? 0) + 1;
            }
            
            if (isset($analysis['primary_mood'])) {
                $mood = $analysis['primary_mood'];
                $moodCounts[$mood] = ($moodCounts[$mood] ?? 0) + 1;
            }
        }

        return [
            'popular_genres' => array_slice(arsort($genreCounts) ? $genreCounts : [], 0, 5, true),
            'popular_moods' => array_slice(arsort($moodCounts) ? $moodCounts : [], 0, 5, true)
        ];
    }

    /**
     * Scope pour les recherches récentes
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('search_timestamp', '>=', now()->subDays($days));
    }

    /**
     * Scope pour les recherches avec feedback positif
     */
    public function scopePositiveFeedback($query)
    {
        return $query->where('satisfaction_score', '>=', 4.0);
    }

    /**
     * Scope pour les recherches par session
     */
    public function scopeBySession($query, $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }
}