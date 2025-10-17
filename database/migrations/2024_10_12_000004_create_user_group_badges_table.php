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
        Schema::create('user_group_badges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('group_id')->constrained()->onDelete('cascade');
            $table->foreignId('badge_id')->references('id')->on('group_badges')->onDelete('cascade');
            $table->timestamp('earned_at');
            $table->text('achievement_note')->nullable(); // Note sur l'accomplissement
            $table->boolean('is_showcased')->default(false); // Badge mis en avant
            $table->timestamps();
            
            // Un utilisateur ne peut avoir qu'un badge par groupe
            $table->unique(['user_id', 'group_id', 'badge_id']);
            
            // Index pour les requêtes
            $table->index(['user_id', 'group_id']);
            $table->index(['badge_id', 'earned_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_group_badges');
    }
};