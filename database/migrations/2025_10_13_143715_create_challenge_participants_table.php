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
        Schema::create('challenge_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('challenge_id')->constrained('reading_challenges')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Participation
            $table->datetime('joined_at')->default(now());
            $table->enum('status', ['active', 'completed', 'abandoned'])->default('active');
            $table->text('motivation_message')->nullable(); // message de motivation en s'inscrivant
            
            // Progression
            $table->json('progress_data'); // progression spécifique (livres lus, pages, etc.)
            $table->integer('progress_percentage')->default(0);
            $table->datetime('last_update')->nullable();
            
            // Completion
            $table->datetime('completed_at')->nullable();
            $table->json('completion_data')->nullable(); // données de completion
            $table->text('completion_notes')->nullable(); // notes finales du participant
            
            // Récompenses
            $table->json('earned_rewards')->nullable(); // badges ou points gagnés
            $table->integer('points_earned')->default(0);
            
            $table->timestamps();
            
            // Contrainte d'unicité pour éviter les doublons
            $table->unique(['challenge_id', 'user_id']);
            
            // Index pour les requêtes
            $table->index(['challenge_id', 'status']);
            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('challenge_participants');
    }
};
