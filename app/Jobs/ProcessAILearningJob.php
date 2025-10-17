<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\UserInteraction;
use App\Models\UserPreference;
use App\Services\AIRecommendationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ProcessAILearningJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userId;
    protected $batchSize;

    public $timeout = 300; // 5 minutes
    public $maxExceptions = 3;

    /**
     * Create a new job instance.
     */
    public function __construct($userId = null, $batchSize = 100)
    {
        $this->userId = $userId;
        $this->batchSize = $batchSize;
    }

    /**
     * Execute the job.
     */
    public function handle(AIRecommendationService $recommendationService)
    {
        try {
            Log::info('🤖 Début du traitement IA', [
                'user_id' => $this->userId,
                'batch_size' => $this->batchSize
            ]);

            if ($this->userId) {
                // Traiter un utilisateur spécifique
                $this->processUserLearning($this->userId, $recommendationService);
            } else {
                // Traiter tous les utilisateurs actifs
                $this->processAllActivUsersLearning($recommendationService);
            }

            Log::info('✅ Traitement IA terminé avec succès');

        } catch (\Exception $e) {
            Log::error('❌ Erreur dans ProcessAILearningJob: ' . $e->getMessage(), [
                'user_id' => $this->userId,
                'trace' => $e->getTraceAsString()
            ]);
            
            throw $e;
        }
    }

    /**
     * Traiter l'apprentissage pour un utilisateur spécifique
     */
    protected function processUserLearning($userId, AIRecommendationService $recommendationService)
    {
        $user = User::find($userId);
        if (!$user) {
            Log::warning("Utilisateur {$userId} non trouvé");
            return;
        }

        // Analyser les interactions récentes
        $this->analyzeRecentInteractions($userId);
        
        // Mettre à jour les préférences
        $this->updateUserPreferences($userId);
        
        // Calculer les similarités avec d'autres utilisateurs
        $this->calculateUserSimilarities($userId);
        
        // Générer de nouvelles recommandations
        $this->generateFreshRecommendations($userId, $recommendationService);
        
        Log::info("✨ Apprentissage IA terminé pour l'utilisateur {$userId}");
    }

    /**
     * Traiter l'apprentissage pour tous les utilisateurs actifs
     */
    protected function processAllActivUsersLearning(AIRecommendationService $recommendationService)
    {
        $activeUsers = User::whereHas('interactions', function ($query) {
            $query->where('timestamp', '>=', Carbon::now()->subDays(7));
        })->take($this->batchSize)->pluck('id');

        Log::info("📊 Traitement de {$activeUsers->count()} utilisateurs actifs");

        foreach ($activeUsers as $userId) {
            try {
                $this->processUserLearning($userId, $recommendationService);
            } catch (\Exception $e) {
                Log::warning("Erreur pour l'utilisateur {$userId}: " . $e->getMessage());
            }
        }
    }

    /**
     * Analyser les interactions récentes
     */
    protected function analyzeRecentInteractions($userId)
    {
        $recentInteractions = UserInteraction::where('user_id', $userId)
            ->where('timestamp', '>=', Carbon::now()->subDays(30))
            ->with('book.category')
            ->get();

        if ($recentInteractions->isEmpty()) {
            return;
        }

        // Analyser les patterns de comportement
        $patterns = $this->extractBehaviorPatterns($recentInteractions);
        
        // Stocker les insights dans le cache pour utilisation future
        cache()->put(
            "user_behavior_patterns_{$userId}",
            $patterns,
            Carbon::now()->addDays(7)
        );

        Log::debug("📈 Patterns analysés pour l'utilisateur {$userId}", $patterns);
    }

    /**
     * Extraire les patterns de comportement
     */
    protected function extractBehaviorPatterns($interactions)
    {
        $patterns = [
            'preferred_categories' => [],
            'interaction_frequency' => [],
            'time_patterns' => [],
            'engagement_score' => 0,
            'reading_habits' => []
        ];

        // Analyser les catégories préférées
        $categoryInteractions = $interactions->groupBy('book.category.name');
        foreach ($categoryInteractions as $category => $categoryInteractionGroup) {
            $totalScore = $categoryInteractionGroup->sum(function ($interaction) {
                return $interaction->interaction_value * UserInteraction::getInteractionWeight($interaction->interaction_type);
            });
            
            $patterns['preferred_categories'][$category] = $totalScore / $categoryInteractionGroup->count();
        }

        // Analyser les patterns temporels
        $hourlyActivity = $interactions->groupBy(function ($interaction) {
            return $interaction->timestamp->hour;
        });

        foreach ($hourlyActivity as $hour => $hourInteractions) {
            $patterns['time_patterns'][$hour] = $hourInteractions->count();
        }

        // Calculer le score d'engagement global
        $totalEngagement = $interactions->sum(function ($interaction) {
            return $interaction->interaction_value * UserInteraction::getInteractionWeight($interaction->interaction_type);
        });
        
        $patterns['engagement_score'] = $totalEngagement / max(1, $interactions->count());

        // Analyser les habitudes de lecture
        $readingInteractions = $interactions->where('interaction_type', 'read_time');
        if ($readingInteractions->isNotEmpty()) {
            $avgReadingTime = $readingInteractions->avg('duration_seconds') / 60; // en minutes
            $patterns['reading_habits'] = [
                'avg_session_minutes' => round($avgReadingTime, 2),
                'total_sessions' => $readingInteractions->count(),
                'preferred_reading_times' => $this->getPreferredReadingTimes($readingInteractions)
            ];
        }

        return $patterns;
    }

    /**
     * Obtenir les heures de lecture préférées
     */
    protected function getPreferredReadingTimes($readingInteractions)
    {
        $timeSlots = [
            'morning' => 0,    // 6-12
            'afternoon' => 0,  // 12-18
            'evening' => 0,    // 18-22
            'night' => 0       // 22-6
        ];

        foreach ($readingInteractions as $interaction) {
            $hour = $interaction->timestamp->hour;
            
            if ($hour >= 6 && $hour < 12) {
                $timeSlots['morning']++;
            } elseif ($hour >= 12 && $hour < 18) {
                $timeSlots['afternoon']++;
            } elseif ($hour >= 18 && $hour < 22) {
                $timeSlots['evening']++;
            } else {
                $timeSlots['night']++;
            }
        }

        return $timeSlots;
    }

    /**
     * Mettre à jour les préférences utilisateur
     */
    protected function updateUserPreferences($userId)
    {
        $patterns = cache()->get("user_behavior_patterns_{$userId}");
        if (!$patterns || !isset($patterns['preferred_categories'])) {
            return;
        }

        foreach ($patterns['preferred_categories'] as $categoryName => $score) {
            // Trouver l'ID de la catégorie
            $category = \App\Models\Category::where('name', $categoryName)->first();
            if (!$category) continue;

            // Normaliser le score entre 0 et 1
            $normalizedScore = min(1.0, $score / 10);
            
            // Calculer la confiance basée sur le nombre d'interactions
            $confidence = min(1.0, $patterns['engagement_score'] / 50);

            UserPreference::updatePreference(
                $userId,
                $category->id,
                $normalizedScore,
                'genre', // preference_type
                'implicit', // learning_source
                $confidence
            );
        }

        Log::debug("🎯 Préférences mises à jour pour l'utilisateur {$userId}");
    }

    /**
     * Calculer les similarités avec d'autres utilisateurs
     */
    protected function calculateUserSimilarities($userId)
    {
        // Trouver les utilisateurs similaires et cacher le résultat
        $similarUsers = UserPreference::findSimilarUsers($userId, 20, 0.2);
        
        cache()->put(
            "similar_users_{$userId}",
            $similarUsers,
            Carbon::now()->addDays(7)
        );

        Log::debug("👥 {count} utilisateurs similaires trouvés pour {$userId}", [
            'count' => count($similarUsers),
            'similar_users' => array_keys($similarUsers)
        ]);
    }

    /**
     * Générer de nouvelles recommandations
     */
    protected function generateFreshRecommendations($userId, AIRecommendationService $recommendationService)
    {
        try {
            // Vider le cache existant
            cache()->forget("ai_recommendations_{$userId}");
            
            // Générer de nouvelles recommandations
            $recommendations = $recommendationService->generateRecommendations($userId, 20);
            
            Log::debug("🎁 {count} nouvelles recommandations générées pour l'utilisateur {$userId}", [
                'count' => $recommendations->count()
            ]);

        } catch (\Exception $e) {
            Log::warning("Erreur génération recommandations pour {$userId}: " . $e->getMessage());
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception)
    {
        Log::error('❌ ProcessAILearningJob failed', [
            'user_id' => $this->userId,
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);
    }
}