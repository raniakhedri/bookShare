<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\AIRecommendationService;
use Symfony\Component\HttpFoundation\Response;

class TrackAIInteractions
{
    protected $recommendationService;

    public function __construct(AIRecommendationService $recommendationService)
    {
        $this->recommendationService = $recommendationService;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Vérifier si l'utilisateur est connecté
        if (!Auth::check()) {
            return $response;
        }

        $user = Auth::user();
        $route = $request->route();

        // Tracker les interactions automatiquement selon la route
        if ($route) {
            $this->trackRouteInteraction($user->id, $route, $request);
        }

        return $response;
    }

    /**
     * Tracker les interactions basées sur la route
     */
    protected function trackRouteInteraction($userId, $route, Request $request)
    {
        $routeName = $route->getName();
        $parameters = $route->parameters();

        try {
            // Tracker la consultation d'un livre
            if ($routeName === 'books.show' || $routeName === 'frontoffice.book.show') {
                if (isset($parameters['book']) || isset($parameters['id'])) {
                    $bookId = $parameters['book'] ?? $parameters['id'];
                    
                    $this->recommendationService->recordUserInteraction(
                        $userId,
                        $bookId,
                        'view',
                        1.0,
                        null
                    );
                }
            }

            // Tracker les recherches
            if ($routeName === 'books.search' || str_contains($routeName, 'search')) {
                $searchQuery = $request->get('q') ?? $request->get('search');
                if ($searchQuery) {
                    // Créer une interaction de recherche générale
                    $this->recordSearchInteraction($userId, $searchQuery);
                }
            }

            // Tracker la navigation dans la marketplace
            if (str_contains($routeName, 'marketplace')) {
                $this->recordMarketplaceInteraction($userId, $routeName);
            }

            // Tracker l'accès aux recommandations IA
            if ($routeName === 'ai.recommendations') {
                $this->recordAIPageInteraction($userId);
            }

        } catch (\Exception $e) {
            // Logger l'erreur mais ne pas interrompre la requête
            \Log::warning('Erreur tracking IA: ' . $e->getMessage());
        }
    }

    /**
     * Enregistrer une interaction de recherche
     */
    protected function recordSearchInteraction($userId, $searchQuery)
    {
        // Stocker la requête de recherche pour analyse future
        \Cache::put(
            "user_search_{$userId}_" . time(),
            $searchQuery,
            now()->addDays(30)
        );

        // Enregistrer comme interaction générale
        $this->recommendationService->recordUserInteraction(
            $userId,
            0, // Pas de livre spécifique
            'search',
            0.5,
            null
        );
    }

    /**
     * Enregistrer une interaction marketplace
     */
    protected function recordMarketplaceInteraction($userId, $routeName)
    {
        $interactionValue = 0.3;

        // Valeur différente selon l'action
        if (str_contains($routeName, 'create') || str_contains($routeName, 'store')) {
            $interactionValue = 0.8; // Action forte
        } elseif (str_contains($routeName, 'my-books') || str_contains($routeName, 'my-requests')) {
            $interactionValue = 0.6; // Gestion personnelle
        }

        $this->recommendationService->recordUserInteraction(
            $userId,
            0,
            'view',
            $interactionValue,
            null
        );
    }

    /**
     * Enregistrer l'accès à la page IA
     */
    protected function recordAIPageInteraction($userId)
    {
        $this->recommendationService->recordUserInteraction(
            $userId,
            0,
            'view',
            0.4,
            null
        );
    }
}