<?php

namespace App\Services;

use App\Models\ReadingChallenge;
use App\Models\Group;
use App\Models\Category;
use App\Models\User;
use App\Models\Book;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AIReadingChallengeGenerator
{
    /**
     * Templates de défis par catégorie
     */
    private const CATEGORY_TEMPLATES = [
        'Science Fiction' => [
            'monthly_genre' => [
                'title' => 'Exploration Sci-Fi : {genre}',
                'description' => 'Ce mois-ci, plongeons dans l\'univers fascinant de {genre} ! Explorez de nouveaux mondes, technologies futuristes et questionnements sur l\'humanité. Partagez vos découvertes et débattez des visions d\'avenir proposées par les auteurs.',
                'genres' => ['cyberpunk', 'space opera', 'dystopie', 'hard science', 'steampunk'],
                'objectives' => ['target_books' => 2, 'share_reviews' => true],
                'difficulty' => 'medium'
            ],
            'author_focus' => [
                'title' => 'Dans l\'univers de {author}',
                'description' => 'Découvrons ensemble l\'œuvre de {author}, maître de la science-fiction ! Explorez ses différents cycles, suivez l\'évolution de sa pensée et analysez ses thèmes récurrents.',
                'authors' => ['Isaac Asimov', 'Philip K. Dick', 'Ursula K. Le Guin', 'Frank Herbert', 'Arthur C. Clarke'],
                'objectives' => ['target_books' => 3, 'compare_works' => true],
                'difficulty' => 'hard'
            ],
            'cultural_discovery' => [
                'title' => 'SF {culture} : Nouvelles Perspectives',
                'description' => 'Explorons la science-fiction {culture} ! Découvrez comment différentes cultures imaginent l\'avenir et enrichissez votre vision du genre.',
                'cultures' => ['japonaise', 'africaine', 'latino-américaine', 'chinoise', 'russe'],
                'objectives' => ['target_books' => 2, 'cultural_analysis' => true],
                'difficulty' => 'medium'
            ]
        ],
        
        'Littérature Classique' => [
            'classic_revival' => [
                'title' => 'Renaissance Classique : {period}',
                'description' => 'Replongeons dans les chefs-d\'œuvre du {period} ! Redécouvrez les textes fondateurs qui ont façonné notre culture littéraire et résonnent encore aujourd\'hui.',
                'periods' => ['XIXe siècle', 'siècle des Lumières', 'romantisme', 'réalisme', 'symbolisme'],
                'objectives' => ['target_books' => 2, 'historical_context' => true],
                'difficulty' => 'hard'
            ],
            'author_focus' => [
                'title' => 'L\'univers de {author}',
                'description' => 'Plongez dans l\'œuvre complète de {author} ! Suivez l\'évolution de son style, analysez ses thèmes récurrents et comprenez son impact sur la littérature.',
                'authors' => ['Victor Hugo', 'Balzac', 'Zola', 'Flaubert', 'Maupassant'],
                'objectives' => ['target_books' => 2, 'style_analysis' => true],
                'difficulty' => 'hard'
            ]
        ],
        
        'Fantasy' => [
            'monthly_genre' => [
                'title' => 'Aventure Fantasy : {subgenre}',
                'description' => 'Embarquons pour une aventure épique dans l\'univers de la {subgenre} ! Explorez des mondes magiques, suivez des héros légendaires et laissez-vous emporter par l\'imaginaire.',
                'genres' => ['high fantasy', 'dark fantasy', 'urban fantasy', 'fantasy historique', 'fantasy jeunesse'],
                'objectives' => ['target_books' => 2, 'world_building_analysis' => true],
                'difficulty' => 'medium'
            ]
        ],
        
        'Polar & Thriller' => [
            'monthly_genre' => [
                'title' => 'Enquête {style}',
                'description' => 'Ce mois-ci, menons l\'enquête avec des {style} captivants ! Aiguisez votre sens de la déduction et laissez-vous surprendre par les rebondissements.',
                'genres' => ['polar nordique', 'cozy mystery', 'thriller psychologique', 'roman noir', 'police procedural'],
                'objectives' => ['target_books' => 3, 'mystery_solving' => true],
                'difficulty' => 'easy'
            ]
        ],
        
        'Romance' => [
            'monthly_genre' => [
                'title' => 'Romance {type}',
                'description' => 'Plongeons dans l\'univers tendre de la romance {type} ! Explorez les différentes facettes de l\'amour et laissez-vous emporter par les émotions.',
                'genres' => ['contemporaine', 'historique', 'paranormale', 'young adult', 'comedy'],
                'objectives' => ['target_books' => 3, 'emotion_sharing' => true],
                'difficulty' => 'easy'
            ]
        ]
    ];

    /**
     * Générateur principal de défis
     */
    public function generateChallenge(Group $group, array $options = [])
    {
        $category = $group->category;
        $groupStats = $this->analyzeGroupReadingHabits($group);
        
        $challengeType = $options['type'] ?? $this->selectOptimalChallengeType($groupStats);
        $difficulty = $options['difficulty'] ?? $this->calculateOptimalDifficulty($groupStats);
        
        $template = $this->getCategoryTemplate($category, $challengeType);
        if (!$template) {
            $template = $this->getGenericTemplate($challengeType);
        }

        $challengeData = $this->buildChallengeFromTemplate($template, $group, $difficulty);
        
        return $this->createChallenge($challengeData, $group);
    }

    /**
     * Analyser les habitudes de lecture du groupe
     */
    private function analyzeGroupReadingHabits(Group $group)
    {
        $members = $group->members;
        $stats = [
            'total_members' => $members->count(),
            'active_readers' => 0,
            'avg_books_per_month' => 0,
            'popular_genres' => [],
            'reading_level' => 'medium',
            'last_activity' => null
        ];

        if ($stats['total_members'] === 0) {
            return $stats;
        }

        // Analyser l'activité récente (posts, livres partagés, etc.)
        $recentActivity = DB::table('posts')
            ->where('group_id', $group->id)
            ->where('created_at', '>=', now()->subDays(30))
            ->count();

        $stats['active_readers'] = max(1, intval($recentActivity / 10)); // Estimation
        $stats['last_activity'] = now()->subDays(rand(1, 7));

        // Simuler des données de lecture basées sur la catégorie
        $categoryName = $group->category->name ?? 'Général';
        $stats = $this->simulateReadingStats($stats, $categoryName);

        return $stats;
    }

    /**
     * Simuler des statistiques de lecture réalistes
     */
    private function simulateReadingStats(array $stats, string $categoryName)
    {
        switch ($categoryName) {
            case 'Science Fiction':
                $stats['avg_books_per_month'] = 2.5;
                $stats['reading_level'] = 'medium';
                $stats['popular_genres'] = ['space opera', 'cyberpunk', 'dystopie'];
                break;
                
            case 'Littérature Classique':
                $stats['avg_books_per_month'] = 1.5;
                $stats['reading_level'] = 'hard';
                $stats['popular_genres'] = ['romantisme', 'réalisme', 'naturalisme'];
                break;
                
            case 'Fantasy':
                $stats['avg_books_per_month'] = 3.0;
                $stats['reading_level'] = 'medium';
                $stats['popular_genres'] = ['high fantasy', 'urban fantasy'];
                break;
                
            case 'Polar & Thriller':
                $stats['avg_books_per_month'] = 3.5;
                $stats['reading_level'] = 'easy';
                $stats['popular_genres'] = ['polar nordique', 'thriller psychologique'];
                break;
                
            default:
                $stats['avg_books_per_month'] = 2.0;
                $stats['reading_level'] = 'medium';
                $stats['popular_genres'] = ['roman', 'nouvelles'];
        }

        return $stats;
    }

    /**
     * Sélectionner le type de défi optimal
     */
    private function selectOptimalChallengeType(array $stats)
    {
        $types = ['monthly_genre', 'author_focus', 'cultural_discovery', 'page_challenge'];
        
        // Logique basée sur les stats du groupe
        if ($stats['avg_books_per_month'] >= 3) {
            return 'speed_reading';
        } elseif ($stats['reading_level'] === 'hard') {
            return 'author_focus';
        } elseif (count($stats['popular_genres']) > 0) {
            return 'monthly_genre';
        } else {
            return 'cultural_discovery';
        }
    }

    /**
     * Calculer la difficulté optimale
     */
    private function calculateOptimalDifficulty(array $stats)
    {
        if ($stats['avg_books_per_month'] >= 3 && $stats['reading_level'] === 'hard') {
            return 'hard';
        } elseif ($stats['avg_books_per_month'] <= 1.5) {
            return 'easy';
        } else {
            return 'medium';
        }
    }

    /**
     * Obtenir le template de défi pour une catégorie
     */
    private function getCategoryTemplate(Category $category, string $challengeType)
    {
        $categoryName = $category->name ?? 'Général';
        
        return self::CATEGORY_TEMPLATES[$categoryName][$challengeType] ?? null;
    }

    /**
     * Template générique de fallback
     */
    private function getGenericTemplate(string $challengeType)
    {
        $genericTemplates = [
            'monthly_genre' => [
                'title' => 'Défi Découverte : {genre}',
                'description' => 'Ce mois-ci, explorons ensemble un nouveau genre littéraire ! Sortez de votre zone de confort et partagez vos découvertes avec le groupe.',
                'genres' => ['biographie', 'essai', 'théâtre', 'poésie', 'nouvelle'],
                'objectives' => ['target_books' => 2],
                'difficulty' => 'medium'
            ],
            'author_focus' => [
                'title' => 'Focus Auteur : {author}',
                'description' => 'Plongeons dans l\'univers d\'un auteur remarquable ! Explorez plusieurs de ses œuvres et analysez l\'évolution de son style.',
                'authors' => ['auteur contemporain', 'auteur classique', 'auteur international'],
                'objectives' => ['target_books' => 2],
                'difficulty' => 'medium'
            ],
            'page_challenge' => [
                'title' => 'Défi {pages} Pages',
                'description' => 'Relevons ensemble le défi de lire {pages} pages ce mois-ci ! Peu importe le genre, l\'important est de maintenir le rythme.',
                'pages' => [500, 750, 1000, 1500],
                'objectives' => ['target_pages' => 1000],
                'difficulty' => 'medium'
            ]
        ];

        return $genericTemplates[$challengeType] ?? $genericTemplates['monthly_genre'];
    }

    /**
     * Construire les données du défi à partir du template
     */
    private function buildChallengeFromTemplate(array $template, Group $group, string $difficulty)
    {
        $title = $template['title'];
        $description = $template['description'];
        
        // Remplacer les placeholders
        if (isset($template['genres'])) {
            $genre = $template['genres'][array_rand($template['genres'])];
            $title = str_replace('{genre}', $genre, $title);
            $title = str_replace('{subgenre}', $genre, $title);
            $title = str_replace('{style}', $genre, $title);
            $title = str_replace('{type}', $genre, $title);
            $description = str_replace(['{genre}', '{subgenre}', '{style}', '{type}'], $genre, $description);
        }
        
        if (isset($template['authors'])) {
            $author = $template['authors'][array_rand($template['authors'])];
            $title = str_replace('{author}', $author, $title);
            $description = str_replace('{author}', $author, $description);
        }
        
        if (isset($template['cultures'])) {
            $culture = $template['cultures'][array_rand($template['cultures'])];
            $title = str_replace('{culture}', $culture, $title);
            $description = str_replace('{culture}', $culture, $description);
        }
        
        if (isset($template['periods'])) {
            $period = $template['periods'][array_rand($template['periods'])];
            $title = str_replace('{period}', $period, $title);
            $description = str_replace('{period}', $period, $description);
        }
        
        if (isset($template['pages'])) {
            $pages = $template['pages'][array_rand($template['pages'])];
            $title = str_replace('{pages}', $pages, $title);
            $description = str_replace('{pages}', $pages, $description);
        }

        // Adapter les objectifs selon la difficulté
        $objectives = $template['objectives'] ?? [];
        $objectives = $this->adjustObjectivesForDifficulty($objectives, $difficulty);

        return [
            'title' => $title,
            'description' => $description,
            'objectives' => $objectives,
            'difficulty' => $difficulty,
            'template_used' => $template
        ];
    }

    /**
     * Ajuster les objectifs selon la difficulté
     */
    private function adjustObjectivesForDifficulty(array $objectives, string $difficulty)
    {
        $multipliers = [
            'easy' => 0.7,
            'medium' => 1.0,
            'hard' => 1.5
        ];

        $multiplier = $multipliers[$difficulty] ?? 1.0;

        if (isset($objectives['target_books'])) {
            $objectives['target_books'] = max(1, intval($objectives['target_books'] * $multiplier));
        }
        
        if (isset($objectives['target_pages'])) {
            $objectives['target_pages'] = intval($objectives['target_pages'] * $multiplier);
        }

        return $objectives;
    }

    /**
     * Créer le défi en base de données
     */
    private function createChallenge(array $challengeData, Group $group)
    {
        $challengeType = $this->determineChallengeType($challengeData['template_used']);
        $duration = $this->getChallengeDuration($challengeType);

        return ReadingChallenge::create([
            'group_id' => $group->id,
            'category_id' => $group->category_id,
            'creator_id' => 1, // Système AI (vous pouvez créer un user spécial "AI")
            'title' => $challengeData['title'],
            'description' => $challengeData['description'],
            'challenge_type' => $challengeType,
            'difficulty_level' => $challengeData['difficulty'],
            'objectives' => $challengeData['objectives'],
            'criteria' => $this->generateCriteria($challengeType, $challengeData['objectives']),
            'rewards' => $this->generateRewards($challengeData['difficulty']),
            'start_date' => now(),
            'end_date' => now()->addDays($duration),
            'max_participants' => null, // Illimité par défaut
            'status' => 'active',
            'is_ai_generated' => true,
            'ai_context' => [
                'category' => $group->category->name ?? 'Général',
                'group_stats' => $challengeData['template_used'],
                'generation_date' => now()->toDateTimeString()
            ],
            'ai_prompt' => 'Défi généré automatiquement basé sur les habitudes du groupe et la catégorie ' . ($group->category->name ?? 'Général')
        ]);
    }

    /**
     * Déterminer le type de défi
     */
    private function determineChallengeType(array $template)
    {
        if (isset($template['genres'])) return 'monthly_genre';
        if (isset($template['authors'])) return 'author_focus';
        if (isset($template['cultures'])) return 'cultural_discovery';
        if (isset($template['pages'])) return 'page_challenge';
        
        return 'monthly_genre';
    }

    /**
     * Obtenir la durée du défi
     */
    private function getChallengeDuration(string $challengeType)
    {
        return ReadingChallenge::CHALLENGE_TYPES[$challengeType]['duration_days'] ?? 30;
    }

    /**
     * Générer les critères de validation
     */
    private function generateCriteria(string $challengeType, array $objectives)
    {
        $criteria = [
            'completion_required' => true,
            'progress_tracking' => true,
            'sharing_encouraged' => true
        ];

        switch ($challengeType) {
            case 'monthly_genre':
                $criteria['genre_verification'] = true;
                $criteria['minimum_books'] = $objectives['target_books'] ?? 2;
                break;
                
            case 'author_focus':
                $criteria['author_verification'] = true;
                $criteria['minimum_books'] = $objectives['target_books'] ?? 2;
                break;
                
            case 'page_challenge':
                $criteria['page_tracking'] = true;
                $criteria['target_pages'] = $objectives['target_pages'] ?? 1000;
                break;
        }

        return $criteria;
    }

    /**
     * Générer les récompenses
     */
    private function generateRewards(string $difficulty)
    {
        $baseRewards = [
            'badge' => 'Lecteur Défi',
            'points' => 100,
            'certificate' => true
        ];

        $multipliers = [
            'easy' => 1,
            'medium' => 1.5,
            'hard' => 2
        ];

        $multiplier = $multipliers[$difficulty] ?? 1;
        $baseRewards['points'] = intval($baseRewards['points'] * $multiplier);

        return $baseRewards;
    }

    /**
     * Générer des défis pour tous les groupes actifs
     */
    public function generateChallengesForAllGroups()
    {
        $activeGroups = Group::where('status', 'active')
            ->with('category')
            ->get();

        $results = [];

        foreach ($activeGroups as $group) {
            try {
                // Vérifier s'il y a déjà un défi actif
                $hasActiveChallenge = ReadingChallenge::where('group_id', $group->id)
                    ->where('status', 'active')
                    ->where('end_date', '>', now())
                    ->exists();

                if (!$hasActiveChallenge) {
                    $challenge = $this->generateChallenge($group);
                    $results[] = [
                        'group' => $group->name,
                        'challenge' => $challenge->title,
                        'status' => 'created'
                    ];
                } else {
                    $results[] = [
                        'group' => $group->name,
                        'status' => 'skipped - active challenge exists'
                    ];
                }
            } catch (\Exception $e) {
                $results[] = [
                    'group' => $group->name,
                    'status' => 'error: ' . $e->getMessage()
                ];
            }
        }

        return $results;
    }
}