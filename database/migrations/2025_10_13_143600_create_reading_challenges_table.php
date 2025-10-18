<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reading_challenges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('creator_id')->constrained('users')->onDelete('cascade');
            
            // Informations du défi
            $table->string('title');
            $table->text('description');
            $table->string('challenge_type'); // monthly, weekly, genre_exploration, author_focus, etc.
            $table->string('difficulty_level')->default('medium'); // easy, medium, hard
            
            // Objectifs et critères
            $table->json('objectives'); // objectifs spécifiques (nombre de livres, genres, etc.)
            $table->json('criteria'); // critères de validation
            $table->json('rewards')->nullable(); // badges ou récompenses
            
            // Planification
            $table->datetime('start_date');
            $table->datetime('end_date');
            $table->integer('max_participants')->nullable();
            
            // Statut et métriques
            $table->enum('status', ['draft', 'active', 'completed', 'cancelled'])->default('draft');
            $table->integer('participants_count')->default(0);
            $table->json('progress_stats')->nullable(); // statistiques de progression
            
            // Génération AI
            $table->boolean('is_ai_generated')->default(false);
            $table->json('ai_context')->nullable(); // contexte utilisé pour la génération
            $table->text('ai_prompt')->nullable(); // prompt utilisé
            
            $table->timestamps();
            
            // Index pour optimiser les requêtes
            $table->index(['group_id', 'status']);
            $table->index(['category_id', 'challenge_type']);
            $table->index(['start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reading_challenges');
    }
};
