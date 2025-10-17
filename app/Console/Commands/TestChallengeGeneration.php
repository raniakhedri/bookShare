<?php

namespace App\Console\Commands;

use App\Models\Group;
use App\Models\Category;
use App\Services\AIReadingChallengeGenerator;
use Illuminate\Console\Command;

class TestChallengeGeneration extends Command
{
    protected $signature = 'test:challenges';
    protected $description = 'Test la génération de défis IA';

    public function handle()
    {
        $this->info('🧪 Test de génération de défis IA...');

        try {
            // Créer une catégorie de test si elle n'existe pas
            $category = Category::firstOrCreate([
                'name' => 'Science Fiction'
            ], [
                'description' => 'Catégorie de test pour la science-fiction'
            ]);

            // Créer un groupe de test si il n'existe pas
            $group = Group::firstOrCreate([
                'name' => 'Groupe Test SF',
                'theme' => 'Science Fiction'
            ], [
                'description' => 'Groupe de test pour les défis IA',
                'category_id' => $category->id,
                'creator_id' => 1,
                'status' => 'active'
            ]);

            $this->line("📚 Groupe: {$group->name}");
            $this->line("🏷️  Catégorie: {$category->name}");

            // Tester la génération
            $generator = new AIReadingChallengeGenerator();
            $challenge = $generator->generateChallenge($group);

            $this->info('✅ Défi généré avec succès !');
            $this->line("🏆 Titre: {$challenge->title}");
            $this->line("📖 Type: {$challenge->getTypeLabel()}");
            $this->line("⚡ Difficulté: " . ucfirst($challenge->difficulty_level));
            $this->line("🤖 IA: " . ($challenge->is_ai_generated ? 'Oui' : 'Non'));
            
            if ($challenge->objectives) {
                $this->line("🎯 Objectifs:");
                foreach ($challenge->objectives as $key => $value) {
                    $this->line("   • {$key}: {$value}");
                }
            }

            $this->line("📅 Période: {$challenge->start_date->format('d/m/Y')} → {$challenge->end_date->format('d/m/Y')}");

        } catch (\Exception $e) {
            $this->error('❌ Erreur: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
            return 1;
        }

        return 0;
    }
}