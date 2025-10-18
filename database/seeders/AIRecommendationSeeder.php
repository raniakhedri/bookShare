<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Book;
use App\Models\Category;
use App\Models\UserInteraction;
use App\Models\UserPreference;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Faker\Factory as Faker;

class AIRecommendationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🤖 Génération de données de test pour le système IA...');
        
        $faker = Faker::create();
        
        // Obtenir les utilisateurs et livres existants
        $users = User::all();
        $books = Book::with('category')->get();
        $categories = Category::all();

        if ($users->isEmpty() || $books->isEmpty()) {
            $this->command->warn('⚠️ Aucun utilisateur ou livre trouvé. Créez d\'abord des données de base.');
            return;
        }

        $this->command->info("👥 {$users->count()} utilisateurs trouvés");
        $this->command->info("📚 {$books->count()} livres trouvés");
        $this->command->info("🏷️ {$categories->count()} catégories trouvées");

        // Générer des interactions réalistes
        $this->generateRealisticInteractions($users, $books, $faker);
        
        // Générer des préférences initiales
        $this->generateInitialPreferences($users, $categories, $faker);
        
        // Créer des patterns d'utilisation diversifiés
        $this->createDiverseUsagePatterns($users, $books, $faker);

        $this->command->info('✅ Données IA générées avec succès !');
    }

    /**
     * Générer des interactions réalistes
     */
    protected function generateRealisticInteractions($users, $books, $faker)
    {
        $this->command->info('📊 Génération des interactions utilisateur...');
        
        $interactionTypes = ['view', 'like', 'share', 'download', 'read_time', 'rate', 'comment', 'bookmark'];
        $totalInteractions = 0;

        foreach ($users as $user) {
            // Chaque utilisateur a un niveau d'activité différent
            $activityLevel = $faker->randomElement(['low', 'medium', 'high', 'very_high']);
            $interactionCount = $this->getInteractionCountForActivity($activityLevel);
            
            // Sélectionner des livres selon les préférences simulées
            $userBooks = $this->selectBooksForUser($user, $books, $faker);
            
            for ($i = 0; $i < $interactionCount; $i++) {
                $book = $userBooks->random();
                $interactionType = $this->selectWeightedInteractionType($faker);
                $value = $this->calculateInteractionValue($interactionType, $faker);
                
                // Générer une date réaliste (plus récent = plus probable)
                $timestamp = $this->generateRealisticTimestamp($faker);
                
                UserInteraction::create([
                    'user_id' => $user->id,
                    'book_id' => $book->id,
                    'interaction_type' => $interactionType,
                    'interaction_value' => $value,
                    'duration_seconds' => $interactionType === 'read_time' ? $faker->numberBetween(60, 3600) : null,
                    'context_data' => $this->generateContextData($interactionType, $faker),
                    'timestamp' => $timestamp
                ]);
                
                $totalInteractions++;
            }
        }

        $this->command->info("✨ {$totalInteractions} interactions générées");
    }

    /**
     * Obtenir le nombre d'interactions selon le niveau d'activité
     */
    protected function getInteractionCountForActivity($activityLevel)
    {
        switch ($activityLevel) {
            case 'low': return rand(5, 15);
            case 'medium': return rand(15, 40);
            case 'high': return rand(40, 80);
            case 'very_high': return rand(80, 150);
            default: return rand(10, 30);
        }
    }

    /**
     * Sélectionner des livres pour un utilisateur selon ses préférences simulées
     */
    protected function selectBooksForUser($user, $books, $faker)
    {
        // Simuler des préférences de catégories
        $categories = $books->pluck('category')->filter()->unique('id');
        
        if ($categories->isEmpty()) {
            // Si pas de catégories, retourner tous les livres
            return $books;
        }
        
        $preferredCategories = $categories->random(rand(1, min(3, $categories->count())));
        $preferredCategoryIds = collect($preferredCategories)->pluck('id');
        
        // 70% des livres viennent des catégories préférées
        $preferredBooks = $books->whereIn('category.id', $preferredCategoryIds);
        $randomBooks = $books->whereNotIn('category.id', $preferredCategoryIds);
        
        $selectedBooks = collect();
        
        // Ajouter des livres préférés (70%)
        if ($preferredBooks->isNotEmpty()) {
            $count = min(10, $preferredBooks->count());
            $selectedBooks = $selectedBooks->merge($preferredBooks->random(min($count, $preferredBooks->count())));
        }
        
        // Ajouter des livres aléatoires (30%)
        if ($randomBooks->isNotEmpty()) {
            $count = min(5, $randomBooks->count());
            $selectedBooks = $selectedBooks->merge($randomBooks->random(min($count, $randomBooks->count())));
        }
        
        return $selectedBooks->isNotEmpty() ? $selectedBooks : $books;
    }

    /**
     * Sélectionner un type d'interaction avec pondération réaliste
     */
    protected function selectWeightedInteractionType($faker)
    {
        $weights = [
            'view' => 40,      // Le plus fréquent
            'read_time' => 20, // Assez fréquent
            'like' => 15,      // Modéré
            'rate' => 10,      // Moins fréquent
            'bookmark' => 8,   // Rare
            'download' => 5,   // Très rare
            'share' => 2,      // Très rare
        ];
        
        return $faker->randomElement(array_merge(...array_map(
            fn($type, $weight) => array_fill(0, $weight, $type),
            array_keys($weights),
            $weights
        )));
    }

    /**
     * Calculer la valeur d'une interaction
     */
    protected function calculateInteractionValue($interactionType, $faker)
    {
        switch ($interactionType) {
            case 'view': return $faker->randomFloat(1, 0.5, 2.0);
            case 'like': return $faker->randomFloat(1, 3.0, 5.0);
            case 'rate': return $faker->randomFloat(1, 1.0, 5.0);
            case 'read_time': return $faker->randomFloat(1, 2.0, 8.0);
            case 'bookmark': return $faker->randomFloat(1, 6.0, 9.0);
            case 'download': return $faker->randomFloat(1, 7.0, 10.0);
            case 'share': return $faker->randomFloat(1, 4.0, 8.0);
            default: return 1.0;
        }
    }

    /**
     * Générer un timestamp réaliste (plus récent = plus probable)
     */
    protected function generateRealisticTimestamp($faker)
    {
        // Distribution pondérée vers le présent
        $weights = [
            1 => 50,   // Dernières 24h
            7 => 30,   // Dernière semaine
            30 => 15,  // Dernier mois
            90 => 5,   // 3 derniers mois
        ];
        
        $daysAgo = $faker->randomElement(array_merge(...array_map(
            fn($days, $weight) => array_fill(0, $weight, $days),
            array_keys($weights),
            $weights
        )));
        
        return Carbon::now()->subDays(rand(0, $daysAgo))->subMinutes(rand(0, 1440));
    }

    /**
     * Générer des données contextuelles
     */
    protected function generateContextData($interactionType, $faker)
    {
        $baseContext = [
            'timestamp' => time(),
            'page_url' => $faker->url,
            'user_agent' => $faker->userAgent,
            'viewport' => $faker->randomElement(['1920x1080', '1366x768', '768x1024', '375x812']),
        ];

        switch ($interactionType) {
            case 'read_time':
                $baseContext['engagement_type'] = $faker->randomElement(['page_reading', 'pdf_viewing', 'audio_listening']);
                break;
                
            case 'view':
                $baseContext['source'] = $faker->randomElement(['search', 'recommendation', 'category_browse', 'direct']);
                break;
                
            case 'rate':
                $baseContext['rating_context'] = $faker->randomElement(['post_reading', 'quick_review', 'detailed_analysis']);
                break;
        }

        return $baseContext;
    }

    /**
     * Générer des préférences initiales
     */
    protected function generateInitialPreferences($users, $categories, $faker)
    {
        $this->command->info('🎯 Génération des préférences utilisateur...');
        
        $totalPreferences = 0;

        foreach ($users as $user) {
            // Chaque utilisateur a 2-5 catégories préférées
            $preferredCategories = $categories->random(min(rand(2, 5), $categories->count()));
            
            foreach ($preferredCategories as $category) {
                $preferenceScore = $faker->randomFloat(4, 0.3, 1.0);
                $confidenceLevel = $faker->randomFloat(2, 0.4, 0.9);
                
                UserPreference::create([
                    'user_id' => $user->id,
                    'category_id' => $category->id,
                    'preference_score' => $preferenceScore,
                    'preference_type' => 'genre',
                    'learning_source' => $faker->randomElement(['explicit', 'implicit', 'collaborative']),
                    'confidence_level' => $confidenceLevel,
                    'last_updated' => $faker->dateTimeBetween('-30 days', 'now')
                ]);
                
                $totalPreferences++;
            }
        }

        $this->command->info("💝 {$totalPreferences} préférences générées");
    }

    /**
     * Créer des patterns d'utilisation diversifiés
     */
    protected function createDiverseUsagePatterns($users, $books, $faker)
    {
        $this->command->info('📈 Création de patterns d\'utilisation diversifiés...');
        
        // Créer des groupes d'utilisateurs avec des patterns similaires
        $userGroups = $users->chunk(ceil($users->count() / 4));
        $patterns = ['morning_reader', 'evening_reader', 'binge_reader', 'casual_browser'];
        
        foreach ($userGroups as $index => $group) {
            $pattern = $patterns[$index] ?? 'casual_browser';
            $this->applyUsagePattern($group, $books, $pattern, $faker);
        }
    }

    /**
     * Appliquer un pattern d'utilisation spécifique
     */
    protected function applyUsagePattern($users, $books, $pattern, $faker)
    {
        foreach ($users as $user) {
            switch ($pattern) {
                case 'morning_reader':
                    $this->createMorningReaderPattern($user, $books, $faker);
                    break;
                    
                case 'evening_reader':
                    $this->createEveningReaderPattern($user, $books, $faker);
                    break;
                    
                case 'binge_reader':
                    $this->createBingeReaderPattern($user, $books, $faker);
                    break;
                    
                case 'casual_browser':
                    $this->createCasualBrowserPattern($user, $books, $faker);
                    break;
            }
        }
    }

    /**
     * Pattern lecteur matinal
     */
    protected function createMorningReaderPattern($user, $books, $faker)
    {
        // Sessions courtes mais fréquentes le matin
        for ($i = 0; $i < rand(10, 20); $i++) {
            $timestamp = $faker->dateTimeBetween('-30 days', 'now');
            $timestamp->setTime(rand(6, 10), rand(0, 59)); // Matin
            
            UserInteraction::create([
                'user_id' => $user->id,
                'book_id' => $books->random()->id,
                'interaction_type' => 'read_time',
                'interaction_value' => $faker->randomFloat(1, 3.0, 6.0),
                'duration_seconds' => rand(600, 1800), // 10-30 minutes
                'context_data' => ['reading_pattern' => 'morning_routine'],
                'timestamp' => $timestamp
            ]);
        }
    }

    /**
     * Pattern lecteur du soir
     */
    protected function createEveningReaderPattern($user, $books, $faker)
    {
        // Sessions longues le soir
        for ($i = 0; $i < rand(8, 15); $i++) {
            $timestamp = $faker->dateTimeBetween('-30 days', 'now');
            $timestamp->setTime(rand(19, 23), rand(0, 59)); // Soir
            
            UserInteraction::create([
                'user_id' => $user->id,
                'book_id' => $books->random()->id,
                'interaction_type' => 'read_time',
                'interaction_value' => $faker->randomFloat(1, 6.0, 10.0),
                'duration_seconds' => rand(1800, 7200), // 30 minutes - 2 heures
                'context_data' => ['reading_pattern' => 'evening_relaxation'],
                'timestamp' => $timestamp
            ]);
        }
    }

    /**
     * Pattern lecteur intensif
     */
    protected function createBingeReaderPattern($user, $books, $faker)
    {
        // Beaucoup d'activité sur peu de livres
        $favoriteBooks = $books->random(min(rand(3, 5), $books->count()));
        
        foreach ($favoriteBooks as $book) {
            for ($i = 0; $i < rand(15, 30); $i++) {
                UserInteraction::create([
                    'user_id' => $user->id,
                    'book_id' => $book->id,
                    'interaction_type' => $faker->randomElement(['read_time', 'view', 'bookmark']),
                    'interaction_value' => $faker->randomFloat(1, 5.0, 10.0),
                    'duration_seconds' => $faker->randomElement([null, rand(300, 1800)]),
                    'context_data' => ['reading_pattern' => 'intensive_reading'],
                    'timestamp' => $faker->dateTimeBetween('-15 days', 'now')
                ]);
            }
        }
    }

    /**
     * Pattern navigateur occasionnel
     */
    protected function createCasualBrowserPattern($user, $books, $faker)
    {
        // Beaucoup de vues, peu d'engagement profond
        for ($i = 0; $i < rand(20, 40); $i++) {
            UserInteraction::create([
                'user_id' => $user->id,
                'book_id' => $books->random()->id,
                'interaction_type' => $faker->randomElement(['view', 'view', 'view', 'like']), // Plus de vues
                'interaction_value' => $faker->randomFloat(1, 0.5, 3.0),
                'duration_seconds' => null,
                'context_data' => ['reading_pattern' => 'casual_browsing'],
                'timestamp' => $faker->dateTimeBetween('-45 days', 'now')
            ]);
        }
    }
}