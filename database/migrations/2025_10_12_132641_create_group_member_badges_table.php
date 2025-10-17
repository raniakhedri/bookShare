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
        Schema::create('group_member_badges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('groups')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('badge_type'); // top_contributor, expert_month, ambassador, etc.
            $table->string('badge_name'); // Nom du badge
            $table->string('badge_description')->nullable(); // Description du badge
            $table->string('badge_icon')->default('🏆'); // Icône du badge
            $table->string('badge_color')->default('#FFD700'); // Couleur du badge
            $table->integer('points_earned')->default(0); // Points gagnés pour ce badge
            $table->date('earned_date'); // Date d'obtention
            $table->date('expires_at')->nullable(); // Date d'expiration (pour badges temporaires)
            $table->boolean('is_active')->default(true); // Badge actif ou expiré
            $table->json('criteria_met')->nullable(); // Critères remplis pour obtenir le badge
            $table->timestamps();
            
            // Index pour optimiser les requêtes
            $table->index(['group_id', 'user_id']);
            $table->index(['badge_type', 'is_active']);
            $table->index('earned_date');
            
            // Éviter les doublons pour les badges uniques
            $table->unique(['group_id', 'user_id', 'badge_type'], 'unique_user_badge_per_group');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_member_badges');
    }
};
