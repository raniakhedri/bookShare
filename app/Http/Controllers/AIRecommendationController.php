<?php

namespace App\Http\Controllers;

use App\Services\AIRecommendationService;
use App\Models\UserInteraction;
use App\Models\UserPreference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AIRecommendationController extends Controller
{
    protected $recommendationService;

    public function __construct(AIRecommendationService $recommendationService)
    {
        $this->recommendationService = $recommendationService;
    }

    /**
     * Obtenir les recommandations pour l'utilisateur connecté
     */
    public function getRecommendations(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Utilisateur non authentifié'
                ], 401);
            }

            $limit = $request->get('limit', 10);
            $limit = min(50, max(1, $limit)); // Limiter entre 1 et 50

            $recommendations = $this->recommendationService->generateRecommendations(
                $user->id, 
                $limit
            );

            return response()->json([
                'success' => true,
                'data' => [
                    'recommendations' => $recommendations,
                    'user_id' => $user->id,
                    'generated_at' => now()->toISOString(),
                    'total_count' => $recommendations->count()
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('AIRecommendationController::getRecommendations error:', [
                'message' => $e->getMessage(),
                'user_id' => $user->id,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la génération des recommandations',
                'error' => config('app.debug') ? $e->getMessage() : null,
                'data' => [
                    'recommendations' => []
                ]
            ], 500);
        }
    }

    /**
     * Obtenir des recommandations basées sur une description textuelle
     */
    public function getBookRecommendations(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'required|string|min:10|max:500',
            'limit' => 'nullable|integer|min:1|max:10'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Description invalide',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Utilisateur non authentifié'
                ], 401);
            }

            $description = $request->description;
            $limit = $request->get('limit', 3);

            // Générer des recommandations basées sur la description
            $recommendations = $this->recommendationService->generateRecommendationsFromDescription(
                $user->id,
                $description,
                $limit
            );

            // Analyser la description pour les statistiques
            $analysis = $this->recommendationService->analyzeDescription($description);

            // Enregistrer cette recherche comme interaction
            $this->recommendationService->recordSearchInteraction(
                $user->id, 
                $description, 
                $analysis, 
                $recommendations->toArray()
            );

            return response()->json([
                'success' => true,
                'data' => [
                    'recommendations' => $recommendations,
                    'description' => $description,
                    'user_id' => $user->id,
                    'generated_at' => now()->toISOString(),
                    'analysis' => $analysis
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la génération des recommandations',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Obtenir les statistiques de recherche IA
     */
    public function getSearchStats(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Utilisateur non authentifié'
                ], 401);
            }

            $days = $request->get('days', 30);
            $days = min(365, max(1, $days));

            $userStats = \App\Models\AISearchInteraction::getUserSearchStats($user->id, $days);
            $popularTerms = \App\Models\AISearchInteraction::getPopularSearchTerms(5, $days);
            $patterns = \App\Models\AISearchInteraction::getCommonAnalysisPatterns($days);

            return response()->json([
                'success' => true,
                'data' => [
                    'user_stats' => $userStats,
                    'popular_search_terms' => $popularTerms,
                    'analysis_patterns' => $patterns,
                    'period_days' => $days
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des statistiques',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Enregistrer une interaction utilisateur
     */
    public function recordInteraction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'book_id' => 'required|exists:books,id',
            'interaction_type' => 'required|in:view,like,share,download,read_time,search,rate,comment,bookmark,wishlist',
            'interaction_value' => 'nullable|numeric|min:0|max:10',
            'duration_seconds' => 'nullable|integer|min:0',
            'context_data' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données d\'interaction invalides',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Utilisateur non authentifié'
                ], 401);
            }

            $interaction = $this->recommendationService->recordUserInteraction(
                $user->id,
                $request->book_id,
                $request->interaction_type,
                $request->interaction_value ?? 1.0,
                $request->duration_seconds
            );

            return response()->json([
                'success' => true,
                'message' => 'Interaction enregistrée avec succès',
                'data' => [
                    'interaction_id' => $interaction->id,
                    'preferences_updated' => true
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'enregistrement de l\'interaction',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Obtenir les préférences utilisateur
     */
    public function getUserPreferences(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Utilisateur non authentifié'
                ], 401);
            }

            $preferences = UserPreference::getUserPreferences($user->id);
            $metrics = $this->recommendationService->getRecommendationMetrics($user->id);

            return response()->json([
                'success' => true,
                'data' => [
                    'preferences' => $preferences,
                    'metrics' => $metrics,
                    'user_id' => $user->id
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des préférences',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Mettre à jour manuellement une préférence utilisateur
     */
    public function updatePreference(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'preference_score' => 'required|numeric|min:0|max:1',
            'preference_type' => 'required|in:genre,author,language,length,difficulty,theme,format,publication_date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données de préférence invalides',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Utilisateur non authentifié'
                ], 401);
            }

            $preference = UserPreference::updatePreference(
                $user->id,
                $request->category_id,
                $request->preference_score,
                $request->preference_type,
                'explicit', // Source explicite car modifiée manuellement
                0.9 // Confiance élevée pour les préférences explicites
            );

            return response()->json([
                'success' => true,
                'message' => 'Préférence mise à jour avec succès',
                'data' => $preference
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour de la préférence',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Obtenir les statistiques d'interaction
     */
    public function getInteractionStats(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Utilisateur non authentifié'
                ], 401);
            }

            $days = $request->get('days', 30);
            $days = min(365, max(1, $days)); // Limiter entre 1 et 365 jours

            $stats = [
                'total_interactions' => UserInteraction::where('user_id', $user->id)
                    ->where('timestamp', '>=', now()->subDays($days))
                    ->count(),

                'interactions_by_type' => UserInteraction::where('user_id', $user->id)
                    ->where('timestamp', '>=', now()->subDays($days))
                    ->selectRaw('interaction_type, COUNT(*) as count')
                    ->groupBy('interaction_type')
                    ->pluck('count', 'interaction_type'),

                'most_interacted_books' => UserInteraction::where('user_id', $user->id)
                    ->where('timestamp', '>=', now()->subDays($days))
                    ->selectRaw('book_id, COUNT(*) as interaction_count')
                    ->groupBy('book_id')
                    ->orderBy('interaction_count', 'desc')
                    ->with('book')
                    ->take(10)
                    ->get(),

                'daily_activity' => UserInteraction::where('user_id', $user->id)
                    ->where('timestamp', '>=', now()->subDays($days))
                    ->selectRaw('DATE(timestamp) as date, COUNT(*) as count')
                    ->groupBy('date')
                    ->orderBy('date')
                    ->pluck('count', 'date')
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des statistiques',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Interface web pour afficher les recommandations
     */
    public function showRecommendationsPage()
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return redirect()->route('login')->with('error', 'Veuillez vous connecter pour voir vos recommandations.');
            }

            $recommendations = $this->recommendationService->generateRecommendations($user->id, 20);
            $preferences = UserPreference::getUserPreferences($user->id);
            $metrics = $this->recommendationService->getRecommendationMetrics($user->id);

            return view('frontoffice.ai_recommendations', compact('recommendations', 'preferences', 'metrics'));

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors du chargement des recommandations: ' . $e->getMessage());
        }
    }

    /**
     * Feedback sur une recommandation (utile/pas utile)
     */
    public function feedbackRecommendation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'book_id' => 'required|exists:books,id',
            'helpful' => 'required|boolean',
            'reason' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données de feedback invalides',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Utilisateur non authentifié'
                ], 401);
            }

            // Enregistrer le feedback comme une interaction
            $interactionType = $request->helpful ? 'like' : 'dislike';
            $interactionValue = $request->helpful ? 5.0 : 1.0;

            $this->recommendationService->recordUserInteraction(
                $user->id,
                $request->book_id,
                $interactionType,
                $interactionValue,
                null
            );

            return response()->json([
                'success' => true,
                'message' => 'Merci pour votre feedback ! Cela nous aide à améliorer nos recommandations.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'enregistrement du feedback',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}