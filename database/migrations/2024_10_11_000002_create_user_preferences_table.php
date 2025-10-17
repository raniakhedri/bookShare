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
        Schema::create('user_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->decimal('preference_score', 6, 4)->default(0.0000);
            $table->enum('preference_type', [
                'genre', 'author', 'language', 'length', 'difficulty',
                'theme', 'format', 'publication_date'
            ])->default('genre');
            $table->enum('learning_source', [
                'explicit', 'implicit', 'collaborative', 'content', 'hybrid'
            ])->default('implicit');
            $table->decimal('confidence_level', 3, 2)->default(0.50);
            $table->timestamp('last_updated')->useCurrent();
            $table->timestamps();

            // Contrainte unique pour éviter les doublons
            $table->unique(['user_id', 'category_id', 'preference_type']);
            
            // Index pour optimiser les requêtes
            $table->index(['user_id', 'preference_score']);
            $table->index(['preference_type', 'confidence_level']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_preferences');
    }
};