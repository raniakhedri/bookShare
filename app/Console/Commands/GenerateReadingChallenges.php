<?php

namespace App\Console\Commands;

use App\Models\Group;
use App\Services\AIReadingChallengeGenerator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GenerateReadingChallenges extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'challenges:generate 
                            {--group= : ID du groupe spécifique} 
                            {--force : Forcer la génération même si un défi actif existe}
                            {--type= : Type de défi à générer}
                            {--difficulty= : Niveau de difficulté}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Générer des défis de lecture IA pour les groupes actifs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Démarrage de la génération automatique de défis IA...');

        $generator = new AIReadingChallengeGenerator();
        $groupId = $this->option('group');

        // Statistiques de génération
        $stats = [
            'total_groups' => 0,
            'eligible_groups' => 0,
            'challenges_created' => 0,
            'challenges_skipped' => 0,
            'errors' => 0,
            'start_time' => now()
        ];

        if ($groupId) {
            // Générer pour un groupe spécifique
            $group = Group::with('category')->find($groupId);
            
            if (!$group) {
                $this->error("❌ Groupe introuvable avec l'ID: {$groupId}");
                return 1;
            }

            $this->generateForGroup($generator, $group, $stats);
        } else {
            // Génération automatique intelligente pour tous les groupes
            $this->generateAutomatically($generator, $stats);
        }

        $this->displaySummary($stats);
        return 0;
    }

    /**
     * Génération automatique intelligente
     */
    private function generateAutomatically(AIReadingChallengeGenerator $generator, array &$stats)
    {
        $this->line('🧠 Mode génération automatique intelligente activé...');

        // Obtenir les groupes éligibles pour de nouveaux défis
        $eligibleGroups = $this->getEligibleGroups();
        $stats['total_groups'] = Group::where('status', 'active')->count();
        $stats['eligible_groups'] = $eligibleGroups->count();

        $this->line("📊 {$stats['total_groups']} groupes actifs, {$stats['eligible_groups']} éligibles pour nouveaux défis");

        if ($eligibleGroups->isEmpty()) {
            $this->warn('⚠️  Aucun groupe éligible trouvé.');
            return;
        }

        $progressBar = $this->output->createProgressBar($eligibleGroups->count());
        $progressBar->start();

        foreach ($eligibleGroups as $group) {
            try {
                // Analyser le groupe pour déterminer le meilleur type de défi
                $optimalSettings = $this->analyzeGroupForOptimalChallenge($group);
                
                if ($optimalSettings['should_generate']) {
                    $challenge = $generator->generateChallenge($group, $optimalSettings['options']);
                    $stats['challenges_created']++;
                } else {
                    $stats['challenges_skipped']++;
                }

            } catch (\Exception $e) {
                $stats['errors']++;
                \Log::error("Erreur génération automatique défi pour groupe {$group->id}: " . $e->getMessage());
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine();
    }

    /**
     * Obtenir les groupes éligibles pour de nouveaux défis
     */
    private function getEligibleGroups()
    {
        return Group::where('status', 'active')
            ->with(['category', 'readingChallenges' => function($query) {
                $query->where('status', 'active')->where('end_date', '>', now());
            }])
            ->whereHas('users', function($query) {
                $query->where('status', 'accepted');
            }, '>=', 3) // Au moins 3 membres actifs
            ->get()
            ->filter(function($group) {
                // Filtrer les groupes qui n'ont pas de défi actif ou qui en ont besoin d'un nouveau
                $activeChallenge = $group->readingChallenges->first();
                
                if (!$activeChallenge) {
                    return true; // Pas de défi actif, éligible
                }
                
                // Si le défi actuel se termine dans moins de 7 jours, préparer le suivant
                return $activeChallenge->end_date->diffInDays(now()) <= 7;
            });
    }

    /**
     * Analyser un groupe pour déterminer le défi optimal
     */
    private function analyzeGroupForOptimalChallenge(Group $group)
    {
        $analysis = [
            'should_generate' => true,
            'options' => [],
            'reasoning' => []
        ];

        // Analyser l'historique des défis du groupe
        $recentChallenges = $group->readingChallenges()
            ->where('created_at', '>=', now()->subMonths(3))
            ->get();

        // Éviter la répétition de types de défis
        $recentTypes = $recentChallenges->pluck('challenge_type')->unique();
        
        // Analyser l'activité du groupe
        $recentActivity = $group->posts()
            ->where('created_at', '>=', now()->subWeeks(2))
            ->count();

        // Analyser la catégorie du groupe
        $categoryName = $group->category->name ?? 'Général';

        // Logique de décision intelligente
        if ($recentActivity < 2) {
            // Groupe peu actif, défi facile pour stimuler
            $analysis['options']['difficulty'] = 'easy';
            $analysis['options']['type'] = 'monthly_genre';
            $analysis['reasoning'][] = 'Groupe peu actif - défi facile pour relancer';
        } elseif ($recentActivity > 10) {
            // Groupe très actif, on peut proposer du challenging
            $analysis['options']['difficulty'] = 'hard';
            $analysis['reasoning'][] = 'Groupe très actif - défi difficile';
        }

        // Éviter les doublons de types récents
        $availableTypes = ['monthly_genre', 'author_focus', 'cultural_discovery', 'page_challenge'];
        $availableTypes = collect($availableTypes)->diff($recentTypes)->values();
        
        if ($availableTypes->isNotEmpty() && !isset($analysis['options']['type'])) {
            $analysis['options']['type'] = $availableTypes->random();
            $analysis['reasoning'][] = 'Type choisi pour éviter répétition';
        }

        // Analyse saisonnière (optionnel)
        $currentMonth = now()->month;
        if (in_array($currentMonth, [10, 11, 12]) && !$recentTypes->contains('classic_revival')) {
            $analysis['options']['type'] = 'classic_revival';
            $analysis['reasoning'][] = 'Saison automnale - focus sur les classiques';
        }

        return $analysis;
    }

    private function generateForGroup(AIReadingChallengeGenerator $generator, Group $group)
    {
        $this->line("📚 Génération pour le groupe: {$group->name}");

        try {
            // Vérifier si un défi actif existe
            if (!$this->option('force')) {
                $hasActiveChallenge = $group->readingChallenges()
                    ->where('status', 'active')
                    ->where('end_date', '>', now())
                    ->exists();

                if ($hasActiveChallenge) {
                    $this->warn("⚠️  Le groupe '{$group->name}' a déjà un défi actif. Utilisez --force pour forcer la génération.");
                    return;
                }
            }

            $options = array_filter([
                'type' => $this->option('type'),
                'difficulty' => $this->option('difficulty')
            ]);

            $challenge = $generator->generateChallenge($group, $options);

            $this->info("✅ Défi créé: '{$challenge->title}'");
            $this->line("   Type: {$challenge->getTypeLabel()}");
            $this->line("   Difficulté: " . ucfirst($challenge->difficulty_level));
            $this->line("   Catégorie: " . ($group->category->name ?? 'Général'));

        } catch (\Exception $e) {
            $this->error("❌ Erreur pour le groupe '{$group->name}': " . $e->getMessage());
        }
    }

    private function generateForAllGroups(AIReadingChallengeGenerator $generator)
    {
        $this->line('📚 Génération pour tous les groupes actifs...');

        $groups = Group::where('status', 'active')
            ->with('category')
            ->get();

        if ($groups->isEmpty()) {
            $this->warn('⚠️  Aucun groupe actif trouvé.');
            return;
        }

        $this->line("📊 {$groups->count()} groupe(s) trouvé(s)");

        $progressBar = $this->output->createProgressBar($groups->count());
        $progressBar->start();

        $results = ['created' => 0, 'skipped' => 0, 'errors' => 0];

        foreach ($groups as $group) {
            try {
                // Vérifier si un défi actif existe
                if (!$this->option('force')) {
                    $hasActiveChallenge = $group->readingChallenges()
                        ->where('status', 'active')
                        ->where('end_date', '>', now())
                        ->exists();

                    if ($hasActiveChallenge) {
                        $results['skipped']++;
                        $progressBar->advance();
                        continue;
                    }
                }

                $options = array_filter([
                    'type' => $this->option('type'),
                    'difficulty' => $this->option('difficulty')
                ]);

                $challenge = $generator->generateChallenge($group, $options);
                $results['created']++;

            } catch (\Exception $e) {
                $results['errors']++;
                \Log::error("Erreur génération défi pour groupe {$group->id}: " . $e->getMessage());
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        // Afficher les résultats
        $this->info("📊 Résumé de la génération:");
        $this->line("   ✅ Défis créés: {$results['created']}");
        $this->line("   ⏭️  Groupes ignorés: {$results['skipped']}");
        $this->line("   ❌ Erreurs: {$results['errors']}");

            if ($results['created'] > 0) {
            $this->info("🎉 {$results['created']} nouveau(x) défi(s) de lecture généré(s) !");
        }
    }

    /**
     * Afficher le résumé de la génération
     */
    private function displaySummary(array $stats)
    {
        $duration = $stats['start_time']->diffInSeconds(now());
        
        $this->newLine();
        $this->info("📊 Résumé de la génération automatique:");
        $this->line("   ⏱️  Durée: {$duration}s");
        $this->line("   📚 Groupes analysés: {$stats['total_groups']}");
        $this->line("   ✅ Groupes éligibles: {$stats['eligible_groups']}");
        $this->line("   🆕 Défis créés: {$stats['challenges_created']}");
        $this->line("   ⏭️  Défis ignorés: {$stats['challenges_skipped']}");
        $this->line("   ❌ Erreurs: {$stats['errors']}");

        if ($stats['challenges_created'] > 0) {
            $this->info("🎉 Génération automatique terminée avec succès !");
            $this->line("💡 Les nouveaux défis sont maintenant disponibles dans les groupes.");
        } elseif ($stats['eligible_groups'] === 0) {
            $this->warn("⚠️  Aucun groupe éligible pour de nouveaux défis.");
            $this->line("💡 Les groupes ont probablement déjà des défis actifs.");
        } else {
            $this->comment("ℹ️  Aucun nouveau défi généré cette fois-ci.");
        }

        // Conseils pour l'optimisation
        if ($stats['errors'] > 0) {
            $this->warn("⚠️  {$stats['errors']} erreur(s) détectée(s). Consultez les logs pour plus de détails.");
        }

        if ($stats['total_groups'] > 0) {
            $successRate = round(($stats['challenges_created'] / $stats['eligible_groups']) * 100, 1);
            $this->line("📈 Taux de succès: {$successRate}%");
        }
    }
}