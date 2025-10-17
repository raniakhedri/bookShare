<?php

namespace App\Console\Commands;

use App\Jobs\ProcessAILearningJob;
use App\Models\User;
use App\Models\UserInteraction;
use Illuminate\Console\Command;
use Carbon\Carbon;

class ProcessAILearning extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ai:process-learning 
                            {--user= : Process learning for specific user ID}
                            {--batch-size=50 : Number of users to process in batch}
                            {--force : Force processing even for inactive users}
                            {--async : Run jobs asynchronously in queue}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process AI learning algorithms to update user preferences and generate recommendations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🤖 Démarrage du traitement IA...');
        
        $userId = $this->option('user');
        $batchSize = (int) $this->option('batch-size');
        $force = $this->option('force');
        $async = $this->option('async');

        if ($userId) {
            return $this->processSpecificUser($userId, $async);
        }

        return $this->processAllUsers($batchSize, $force, $async);
    }

    /**
     * Traiter un utilisateur spécifique
     */
    protected function processSpecificUser($userId, $async)
    {
        $user = User::find($userId);
        if (!$user) {
            $this->error("❌ Utilisateur {$userId} non trouvé.");
            return 1;
        }

        $this->info("👤 Traitement de l'utilisateur: {$user->name} (ID: {$userId})");

        if ($async) {
            ProcessAILearningJob::dispatch($userId);
            $this->info("📤 Job ajouté à la queue pour traitement asynchrone.");
        } else {
            $job = new ProcessAILearningJob($userId);
            $job->handle(app(\App\Services\AIRecommendationService::class));
            $this->info("✅ Traitement terminé avec succès.");
        }

        return 0;
    }

    /**
     * Traiter tous les utilisateurs
     */
    protected function processAllUsers($batchSize, $force, $async)
    {
        // Statistiques avant traitement
        $this->displayStatistics();

        // Obtenir les utilisateurs à traiter
        $users = $this->getUsersToProcess($batchSize, $force);

        if ($users->isEmpty()) {
            $this->warn('⚠️  Aucun utilisateur à traiter.');
            return 0;
        }

        $this->info("📊 {$users->count()} utilisateurs à traiter (batch size: {$batchSize})");

        if (!$this->confirm('Continuer le traitement ?', true)) {
            $this->info('Traitement annulé.');
            return 0;
        }

        $progressBar = $this->output->createProgressBar($users->count());
        $progressBar->start();

        $processed = 0;
        $errors = 0;

        foreach ($users as $user) {
            try {
                if ($async) {
                    ProcessAILearningJob::dispatch($user->id);
                } else {
                    $job = new ProcessAILearningJob($user->id);
                    $job->handle(app(\App\Services\AIRecommendationService::class));
                }
                
                $processed++;
                $progressBar->advance();

            } catch (\Exception $e) {
                $errors++;
                $this->error("\n❌ Erreur pour l'utilisateur {$user->id}: " . $e->getMessage());
                $progressBar->advance();
            }
        }

        $progressBar->finish();
        $this->newLine(2);

        // Statistiques finales
        $this->info("✅ Traitement terminé:");
        $this->info("   - Utilisateurs traités: {$processed}");
        if ($errors > 0) {
            $this->warn("   - Erreurs: {$errors}");
        }

        if ($async) {
            $this->info("📤 {$processed} jobs ajoutés à la queue pour traitement asynchrone.");
            $this->info("💡 Exécutez 'php artisan queue:work' pour traiter les jobs.");
        }

        return 0;
    }

    /**
     * Obtenir les utilisateurs à traiter
     */
    protected function getUsersToProcess($batchSize, $force)
    {
        $query = User::query();

        if (!$force) {
            // Seulement les utilisateurs avec des interactions récentes
            $query->whereHas('interactions', function ($q) {
                $q->where('timestamp', '>=', Carbon::now()->subDays(30));
            });
        }

        // Prioriser les utilisateurs avec plus d'activité récente
        $query->withCount(['interactions as recent_interactions_count' => function ($q) {
            $q->where('timestamp', '>=', Carbon::now()->subDays(7));
        }])->orderBy('recent_interactions_count', 'desc');

        return $query->take($batchSize)->get();
    }

    /**
     * Afficher les statistiques du système
     */
    protected function displayStatistics()
    {
        $this->info('📊 Statistiques du système IA:');
        $this->table(
            ['Métrique', 'Valeur'],
            [
                ['Utilisateurs total', number_format(User::count())],
                ['Utilisateurs actifs (7j)', number_format(User::whereHas('interactions', function ($q) {
                    $q->where('timestamp', '>=', Carbon::now()->subDays(7));
                })->count())],
                ['Interactions totales', number_format(UserInteraction::count())],
                ['Interactions (24h)', number_format(UserInteraction::where('timestamp', '>=', Carbon::now()->subDay())->count())],
                ['Interactions (7j)', number_format(UserInteraction::where('timestamp', '>=', Carbon::now()->subDays(7))->count())],
                ['Préférences stockées', number_format(\App\Models\UserPreference::count())],
                ['Recommandations en cache', number_format($this->getCachedRecommendationsCount())],
            ]
        );
        $this->newLine();
    }

    /**
     * Compter les recommandations en cache
     */
    protected function getCachedRecommendationsCount()
    {
        try {
            // Pour les systèmes avec Redis
            if (config('cache.default') === 'redis' && cache()->getStore() instanceof \Illuminate\Cache\RedisStore) {
                $pattern = 'ai_recommendations_*';
                $keys = cache()->getStore()->getRedis()->keys($pattern);
                return count($keys ?? []);
            }
            
            // Pour les autres systèmes de cache (file, etc.)
            return 0; // Impossible de compter avec les autres drivers
        } catch (\Exception $e) {
            return 0; // Si Redis n'est pas disponible ou autre erreur
        }
    }

    /**
     * Afficher des conseils d'utilisation
     */
    protected function displayUsageTips()
    {
        $this->info('💡 Conseils d\'utilisation:');
        $this->line('  • Exécutez cette commande régulièrement (quotidien recommandé)');
        $this->line('  • Utilisez --async pour de gros volumes de données');
        $this->line('  • Surveillez les logs dans storage/logs/laravel.log');
        $this->line('  • Configurez une tâche cron pour l\'automatisation');
        $this->newLine();
    }
}