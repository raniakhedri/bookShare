<?php

namespace App\Services;

use App\Models\User;
use App\Models\Book;
use App\Models\UserInteraction;
use App\Models\UserPreference;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AIRecommendationService
{
    protected $cachePrefix = 'ai_recommendations_';
    protected $cacheDuration = 3600; // 1 heure

    /**
     * Générer des recommandations pour un utilisateur
     */
    public function generateRecommendations($userId, $limit = 10)
    {
        $cacheKey = $this->cachePrefix . $userId;

        return Cache::remember($cacheKey, $this->cacheDuration, function () use ($userId, $limit) {
            try {
                // Combiner différents algorithmes de recommandation
                $contentBased = $this->getContentBasedRecommendations($userId, $limit);
                $collaborative = $this->getCollaborativeRecommendations($userId, $limit);
                $trending = $this->getTrendingRecommendations($userId, $limit);
                $contextual = $this->getContextualRecommendations($userId, $limit);

                // Fusionner et pondérer les recommandations
                $recommendations = $this->mergeRecommendations([
                    'content' => ['recommendations' => $contentBased, 'weight' => 0.4],
                    'collaborative' => ['recommendations' => $collaborative, 'weight' => 0.3],
                    'trending' => ['recommendations' => $trending, 'weight' => 0.2],
                    'contextual' => ['recommendations' => $contextual, 'weight' => 0.1]
                ], $limit);

                return $recommendations;

            } catch (\Exception $e) {
                Log::error('Erreur dans generateRecommendations: ' . $e->getMessage(), [
                    'user_id' => $userId,
                    'limit' => $limit,
                    'trace' => $e->getTraceAsString(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]);
                return $this->getFallbackRecommendations($userId, $limit);
            }
        });
    }

    /**
     * Recommandations basées sur le contenu
     */
    protected function getContentBasedRecommendations($userId, $limit)
    {
        $userPreferences = UserPreference::getUserPreferences($userId);
        
        if ($userPreferences->isEmpty()) {
            return collect();
        }

        $recommendedBooks = collect();
        
        foreach ($userPreferences->take(5) as $preference) { // Top 5 préférences
            $books = Book::where('category_id', $preference->category_id)
                ->whereNotIn('id', $this->getUserReadBooks($userId))
                ->where('availability', true)
                ->inRandomOrder()
                ->take(5)
                ->get();

            foreach ($books as $book) {
                $score = $preference->preference_score * $preference->confidence_level;
                $recommendedBooks->push([
                    'book' => $book,
                    'score' => $score,
                    'reason' => 'Basé sur vos préférences pour ' . $preference->category->name
                ]);
            }
        }

        return $recommendedBooks->sortByDesc('score')->take($limit);
    }

    /**
     * Recommandations collaboratives
     */
    protected function getCollaborativeRecommendations($userId, $limit)
    {
        $similarUsers = UserPreference::findSimilarUsers($userId, 20);
        
        if (empty($similarUsers)) {
            return collect();
        }

        $recommendedBooks = collect();
        $userReadBooks = $this->getUserReadBooks($userId);

        foreach ($similarUsers as $similarUserId => $similarity) {
            // Obtenir les livres appréciés par l'utilisateur similaire
            $likedBooks = UserInteraction::where('user_id', $similarUserId)
                ->whereIn('interaction_type', ['LIKE', 'RATE', 'BOOKMARK'])
                ->where('interaction_value', '>', 3.0)
                ->whereNotIn('book_id', $userReadBooks)
                ->with('book')
                ->get();

            foreach ($likedBooks as $interaction) {
                if ($interaction->book && $interaction->book->availability === true) {
                    $score = $similarity * $interaction->interaction_value;
                    $recommendedBooks->push([
                        'book' => $interaction->book,
                        'score' => $score,
                        'reason' => 'Apprécié par des utilisateurs similaires'
                    ]);
                }
            }
        }

        return $recommendedBooks->sortByDesc('score')->take($limit);
    }

    /**
     * Recommandations tendances
     */
    protected function getTrendingRecommendations($userId, $limit)
    {
        $userReadBooks = $this->getUserReadBooks($userId);
        
        $trending = UserInteraction::getMostPopularBooks(50, 7); // 7 derniers jours
        
        return $trending->filter(function ($item) use ($userReadBooks) {
            return !in_array($item->book_id, $userReadBooks) && 
                   $item->book && 
                   $item->book->availability === true;
        })->map(function ($item) {
            return [
                'book' => $item->book,
                'score' => $item->total_score,
                'reason' => 'Tendance populaire cette semaine'
            ];
        })->take($limit);
    }

    /**
     * Recommandations contextuelles (basées sur l'heure, jour, etc.)
     */
    protected function getContextualRecommendations($userId, $limit)
    {
        $hour = now()->hour;
        $dayOfWeek = now()->dayOfWeek;
        
        $contextualCategories = $this->getContextualCategories($hour, $dayOfWeek);
        $userReadBooks = $this->getUserReadBooks($userId);
        
        $recommendedBooks = collect();
        
        foreach ($contextualCategories as $categoryId => $contextScore) {
            $books = Book::where('category_id', $categoryId)
                ->whereNotIn('id', $userReadBooks)
                ->where('availability', true)
                ->inRandomOrder()
                ->take(3)
                ->get();

            foreach ($books as $book) {
                $recommendedBooks->push([
                    'book' => $book,
                    'score' => $contextScore,
                    'reason' => $this->getContextualReason($hour, $dayOfWeek)
                ]);
            }
        }

        return $recommendedBooks->sortByDesc('score')->take($limit);
    }

    /**
     * Obtenir les catégories contextuelles selon l'heure et le jour
     */
    protected function getContextualCategories($hour, $dayOfWeek)
    {
        $categories = [];

        // Recommandations selon l'heure
        if ($hour >= 6 && $hour <= 10) {
            // Matin : développement personnel, actualités
            $categories[1] = 0.8; // Assumant que 1 = Développement personnel
            $categories[2] = 0.7; // Assumant que 2 = Actualités
        } elseif ($hour >= 12 && $hour <= 14) {
            // Pause déjeuner : lecture légère
            $categories[3] = 0.9; // Assumant que 3 = Fiction légère
            $categories[4] = 0.8; // Assumant que 4 = Humour
        } elseif ($hour >= 18 && $hour <= 22) {
            // Soirée : fiction, romans
            $categories[5] = 0.9; // Assumant que 5 = Romans
            $categories[6] = 0.8; // Assumant que 6 = Science-fiction
        }

        // Recommandations selon le jour de la semaine
        if (in_array($dayOfWeek, [0, 6])) { // Weekend
            $categories[7] = 0.7; // Assumant que 7 = Loisirs
            $categories[8] = 0.6; // Assumant que 8 = Voyage
        }

        return $categories;
    }

    /**
     * Obtenir la raison contextuelle
     */
    protected function getContextualReason($hour, $dayOfWeek)
    {
        if ($hour >= 6 && $hour <= 10) {
            return 'Parfait pour commencer la journée';
        } elseif ($hour >= 12 && $hour <= 14) {
            return 'Idéal pour la pause déjeuner';
        } elseif ($hour >= 18 && $hour <= 22) {
            return 'Recommandé pour la soirée';
        } elseif (in_array($dayOfWeek, [0, 6])) {
            return 'Suggestion weekend';
        }

        return 'Recommandation personnalisée';
    }

    /**
     * Fusionner les recommandations de différents algorithmes
     */
    protected function mergeRecommendations($algorithmResults, $limit)
    {
        $finalRecommendations = collect();
        $bookScores = [];

        foreach ($algorithmResults as $algorithm => $data) {
            $weight = $data['weight'];
            $recommendations = $data['recommendations'];

            foreach ($recommendations as $recommendation) {
                $bookId = $recommendation['book']->id;
                $weightedScore = $recommendation['score'] * $weight;

                if (!isset($bookScores[$bookId])) {
                    $bookScores[$bookId] = [
                        'book' => $recommendation['book'],
                        'total_score' => 0,
                        'reasons' => []
                    ];
                }

                $bookScores[$bookId]['total_score'] += $weightedScore;
                $bookScores[$bookId]['reasons'][] = $recommendation['reason'];
            }
        }

        // Convertir en collection et trier
        foreach ($bookScores as $bookId => $data) {
            $finalRecommendations->push([
                'book' => $data['book'],
                'score' => $data['total_score'],
                'reasons' => array_unique($data['reasons']),
                'algorithm' => 'hybrid'
            ]);
        }

        return $finalRecommendations->sortByDesc('score')->take($limit);
    }

    /**
     * Obtenir les livres déjà lus par un utilisateur
     */
    protected function getUserReadBooks($userId)
    {
        return UserInteraction::where('user_id', $userId)
            ->whereIn('interaction_type', ['DOWNLOAD', 'READ_TIME', 'RATE'])
            ->pluck('book_id')
            ->unique()
            ->toArray();
    }

    /**
     * Recommandations de secours en cas d'erreur
     */
    protected function getFallbackRecommendations($userId, $limit)
    {
        return Book::where('availability', true)
            ->whereNotIn('id', $this->getUserReadBooks($userId))
            ->inRandomOrder()
            ->take($limit)
            ->get()
            ->map(function ($book) {
                return [
                    'book' => $book,
                    'score' => 0.5,
                    'reasons' => ['Suggestion générale'],
                    'algorithm' => 'fallback'
                ];
            });
    }

    /**
     * Enregistrer une interaction utilisateur et mettre à jour les préférences
     */
    public function recordUserInteraction($userId, $bookId, $interactionType, $value = 1.0, $duration = null)
    {
        // Enregistrer l'interaction
        $interaction = UserInteraction::recordInteraction(
            $userId, 
            $bookId, 
            $interactionType, 
            $value, 
            $duration
        );

        // Mettre à jour les préférences utilisateur
        $this->updateUserPreferences($userId, $bookId, $interactionType, $value);

        // Invalider le cache des recommandations
        Cache::forget($this->cachePrefix . $userId);

        return $interaction;
    }

    /**
     * Mettre à jour les préférences utilisateur basées sur l'interaction
     */
    protected function updateUserPreferences($userId, $bookId, $interactionType, $value)
    {
        $book = Book::find($bookId);
        if (!$book || !$book->category_id) {
            return;
        }

        $interactionWeight = UserInteraction::getInteractionWeight($interactionType);
        $preferenceScore = ($value * $interactionWeight) / 10; // Normaliser entre 0 et 1
        $confidence = min(1.0, $interactionWeight / 10);

        UserPreference::updatePreference(
            $userId,
            $book->category_id,
            $preferenceScore,
            'genre', // preference_type
            'implicit', // learning_source
            $confidence
        );
    }

    /**
     * Obtenir des métriques sur les recommandations
     */
    public function getRecommendationMetrics($userId, $days = 30)
    {
        $startDate = now()->subDays($days);
        
        return [
            'total_interactions' => UserInteraction::where('user_id', $userId)
                ->where('timestamp', '>=', $startDate)
                ->count(),
            
            'unique_books' => UserInteraction::where('user_id', $userId)
                ->where('timestamp', '>=', $startDate)
                ->distinct('book_id')
                ->count(),
            
            'preference_strength' => UserPreference::where('user_id', $userId)
                ->avg('confidence_level'),
            
            'recommendation_accuracy' => $this->calculateRecommendationAccuracy($userId, $days)
        ];
    }

    /**
     * Calculer la précision des recommandations
     */
    protected function calculateRecommendationAccuracy($userId, $days)
    {
        // Logique pour calculer la précision des recommendations
        // basée sur les interactions positives avec les livres recommandés
        return 0.75; // Placeholder - à implémenter selon vos métriques
    }

    /**
     * Générer des recommandations basées sur une description textuelle
     */
    public function generateRecommendationsFromDescription($userId, $description, $limit = 3)
    {
        try {
            // Analyser la description pour extraire les mots-clés et le sentiment
            $analysis = $this->analyzeDescription($description);
            
            // Obtenir les livres correspondants
            $matchingBooks = $this->findBooksByAnalysis($analysis, $userId);
            
            // Combiner avec les préférences utilisateur
            $personalizedRecommendations = $this->personalizeRecommendations($matchingBooks, $userId);
            
            return $personalizedRecommendations->take($limit);
            
        } catch (\Exception $e) {
            Log::error('Erreur dans generateRecommendationsFromDescription: ' . $e->getMessage());
            return $this->getFallbackRecommendationsFromDescription($userId, $description, $limit);
        }
    }

    /**
     * Analyser une description textuelle pour extraire des informations
     */
    public function analyzeDescription($description)
    {
        $lowerDescription = strtolower($description);
        
        // Dictionnaires de mots-clés par catégorie
        $genreKeywords = [
            'romance' => ['romance', 'love', 'amour', 'romantique', 'relation', 'couple', 'heart', 'passion'],
            'mystery' => ['mystery', 'mystère', 'detective', 'crime', 'murder', 'investigation', 'suspense', 'thriller'],
            'sci-fi' => ['science', 'fiction', 'futur', 'space', 'espace', 'robot', 'technology', 'alien', 'dystopian'],
            'fantasy' => ['fantasy', 'fantastique', 'magic', 'magie', 'wizard', 'dragon', 'medieval', 'quest', 'hero'],
            'historical' => ['historical', 'historique', 'history', 'histoire', 'past', 'period', 'war', 'ancien'],
            'biography' => ['biography', 'biographie', 'life', 'memoir', 'autobiography', 'person', 'famous'],
            'self-help' => ['self-help', 'développement', 'personnel', 'motivation', 'success', 'guide', 'how to'],
            'business' => ['business', 'entrepreneur', 'leadership', 'management', 'strategy', 'marketing'],
            'health' => ['health', 'santé', 'fitness', 'nutrition', 'medical', 'wellness', 'diet'],
            'education' => ['education', 'learning', 'study', 'academic', 'school', 'university', 'research']
        ];

        $moodKeywords = [
            'light' => ['light', 'funny', 'humor', 'comedy', 'amusing', 'entertaining', 'fun'],
            'serious' => ['serious', 'deep', 'profound', 'thought-provoking', 'intense', 'heavy'],
            'inspiring' => ['inspiring', 'motivational', 'uplifting', 'positive', 'hopeful'],
            'dark' => ['dark', 'gritty', 'noir', 'tragic', 'sad', 'depressing']
        ];

        $lengthKeywords = [
            'short' => ['short', 'quick', 'brief', 'novella', 'concise'],
            'long' => ['long', 'epic', 'extensive', 'detailed', 'comprehensive', 'saga']
        ];

        // Analyser les genres
        $detectedGenres = [];
        foreach ($genreKeywords as $genre => $keywords) {
            $score = 0;
            foreach ($keywords as $keyword) {
                if (strpos($lowerDescription, $keyword) !== false) {
                    $score++;
                }
            }
            if ($score > 0) {
                $detectedGenres[$genre] = $score;
            }
        }

        // Analyser l'ambiance
        $detectedMood = [];
        foreach ($moodKeywords as $mood => $keywords) {
            $score = 0;
            foreach ($keywords as $keyword) {
                if (strpos($lowerDescription, $keyword) !== false) {
                    $score++;
                }
            }
            if ($score > 0) {
                $detectedMood[$mood] = $score;
            }
        }

        // Analyser la longueur préférée
        $detectedLength = [];
        foreach ($lengthKeywords as $length => $keywords) {
            $score = 0;
            foreach ($keywords as $keyword) {
                if (strpos($lowerDescription, $keyword) !== false) {
                    $score++;
                }
            }
            if ($score > 0) {
                $detectedLength[$length] = $score;
            }
        }

        return [
            'genres' => $detectedGenres,
            'mood' => $detectedMood,
            'length' => $detectedLength,
            'original_description' => $description,
            'primary_genre' => !empty($detectedGenres) ? array_keys($detectedGenres, max($detectedGenres))[0] : null,
            'primary_mood' => !empty($detectedMood) ? array_keys($detectedMood, max($detectedMood))[0] : null
        ];
    }

    /**
     * Trouver des livres basés sur l'analyse de description
     */
    protected function findBooksByAnalysis($analysis, $userId)
    {
        $userReadBooks = $this->getUserReadBooks($userId);
        $query = Book::where('availability', true)->whereNotIn('id', $userReadBooks);

        // Mapper les genres détectés vers les catégories de la base de données
        $genreCategoryMapping = [
            'romance' => [1, 2], // IDs des catégories romance
            'mystery' => [3, 4], // IDs des catégories mystère
            'sci-fi' => [5], // IDs des catégories sci-fi
            'fantasy' => [6], // IDs des catégories fantasy
            'historical' => [7], // IDs des catégories historique
            'biography' => [8], // IDs des catégories biographie
            'self-help' => [9], // IDs des catégories développement personnel
            'business' => [10], // IDs des catégories business
            'health' => [11], // IDs des catégories santé
            'education' => [12] // IDs des catégories éducation
        ];

        $relevantCategories = [];
        if (!empty($analysis['genres'])) {
            foreach ($analysis['genres'] as $genre => $score) {
                if (isset($genreCategoryMapping[$genre])) {
                    $relevantCategories = array_merge($relevantCategories, $genreCategoryMapping[$genre]);
                }
            }
        }

        if (!empty($relevantCategories)) {
            $query->whereIn('category_id', array_unique($relevantCategories));
        }

        // Recherche textuelle dans le titre et la description
        $description = $analysis['original_description'];
        $searchTerms = explode(' ', $description);
        $searchTerms = array_filter($searchTerms, function($term) {
            return strlen($term) > 3; // Ignorer les mots trop courts
        });

        if (!empty($searchTerms)) {
            $query->where(function($q) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $q->orWhere('title', 'LIKE', "%{$term}%")
                      ->orWhere('description', 'LIKE', "%{$term}%")
                      ->orWhere('author', 'LIKE', "%{$term}%");
                }
            });
        }

        return $query->with('category')->get()->map(function($book) use ($analysis) {
            return [
                'book' => $book,
                'score' => $this->calculateDescriptionMatchScore($book, $analysis),
                'reason' => $this->generateRecommendationReason($book, $analysis),
                'analysis_match' => $analysis
            ];
        });
    }

    /**
     * Calculer le score de correspondance entre un livre et l'analyse
     */
    protected function calculateDescriptionMatchScore($book, $analysis)
    {
        $score = 0.5; // Score de base
        
        // Bonus pour correspondance de genre
        if ($analysis['primary_genre'] && $book->category) {
            $genreCategoryMapping = [
                'romance' => [1, 2],
                'mystery' => [3, 4],
                'sci-fi' => [5],
                'fantasy' => [6],
                'historical' => [7],
                'biography' => [8],
                'self-help' => [9],
                'business' => [10],
                'health' => [11],
                'education' => [12]
            ];
            
            if (isset($genreCategoryMapping[$analysis['primary_genre']]) && 
                in_array($book->category_id, $genreCategoryMapping[$analysis['primary_genre']])) {
                $score += 0.3;
            }
        }

        // Bonus pour correspondance textuelle
        $description = strtolower($analysis['original_description']);
        $bookText = strtolower($book->title . ' ' . $book->description . ' ' . $book->author);
        
        $words = explode(' ', $description);
        $matches = 0;
        foreach ($words as $word) {
            if (strlen($word) > 3 && strpos($bookText, $word) !== false) {
                $matches++;
            }
        }
        
        if (!empty($words)) {
            $score += ($matches / count($words)) * 0.2;
        }

        return min(1.0, $score);
    }

    /**
     * Générer une raison de recommandation
     */
    protected function generateRecommendationReason($book, $analysis)
    {
        $reasons = [];
        
        if ($analysis['primary_genre']) {
            $reasons[] = "Correspond à votre intérêt pour " . ucfirst($analysis['primary_genre']);
        }
        
        if ($analysis['primary_mood']) {
            $moodTranslations = [
                'light' => 'lecture légère',
                'serious' => 'lecture sérieuse',
                'inspiring' => 'lecture inspirante',
                'dark' => 'lecture sombre'
            ];
            $reasons[] = "Parfait pour une " . $moodTranslations[$analysis['primary_mood']];
        }
        
        if (empty($reasons)) {
            $reasons[] = "Correspond à votre description";
        }
        
        return implode(' et ', $reasons);
    }

    /**
     * Personnaliser les recommandations avec les préférences utilisateur
     */
    protected function personalizeRecommendations($matchingBooks, $userId)
    {
        $userPreferences = UserPreference::getUserPreferences($userId);
        
        if ($userPreferences->isEmpty()) {
            return $matchingBooks->sortByDesc('score');
        }

        return $matchingBooks->map(function($recommendation) use ($userPreferences) {
            $book = $recommendation['book'];
            $baseScore = $recommendation['score'];
            
            // Ajuster le score basé sur les préférences utilisateur
            $preferenceBonus = 0;
            $userPref = $userPreferences->where('category_id', $book->category_id)->first();
            
            if ($userPref) {
                $preferenceBonus = $userPref->preference_score * $userPref->confidence_level * 0.2;
            }
            
            $recommendation['score'] = $baseScore + $preferenceBonus;
            $recommendation['personalized'] = true;
            
            return $recommendation;
        })->sortByDesc('score');
    }

    /**
     * Enregistrer une interaction de recherche
     */
    public function recordSearchInteraction($userId, $description, $analysis = null, $recommendations = [])
    {
        // Enregistrer dans la table spécialisée pour les recherches IA
        $aiSearchInteraction = \App\Models\AISearchInteraction::recordSearch(
            $userId,
            $description,
            $analysis,
            $recommendations
        );

        // Enregistrer aussi dans la table générale des interactions
        $userInteraction = UserInteraction::create([
            'user_id' => $userId,
            'book_id' => null, // Pas de livre spécifique
            'interaction_type' => 'search',
            'interaction_value' => 1.0,
            'timestamp' => now(),
            'context_data' => json_encode([
                'search_description' => $description,
                'ai_search_id' => $aiSearchInteraction->id
            ])
        ]);

        return $aiSearchInteraction;
    }

    /**
     * Recommandations de secours pour les descriptions
     */
    protected function getFallbackRecommendationsFromDescription($userId, $description, $limit)
    {
        $userReadBooks = $this->getUserReadBooks($userId);
        
        return Book::where('availability', true)
            ->whereNotIn('id', $userReadBooks)
            ->inRandomOrder()
            ->take($limit)
            ->get()
            ->map(function ($book) use ($description) {
                return [
                    'book' => $book,
                    'score' => 0.4,
                    'reason' => 'Suggestion basée sur votre recherche',
                    'analysis_match' => ['original_description' => $description],
                    'fallback' => true
                ];
            });
    }
}