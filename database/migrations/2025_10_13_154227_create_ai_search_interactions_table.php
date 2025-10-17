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
        Schema::create('ai_search_interactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('search_description');
            $table->json('analysis_result')->nullable(); // Résultat de l'analyse IA
            $table->json('recommendations')->nullable(); // Recommandations générées
            $table->integer('recommendations_count')->default(0);
            $table->decimal('satisfaction_score', 3, 2)->nullable(); // Score de satisfaction 0-5
            $table->text('user_feedback')->nullable();
            $table->string('session_id')->nullable(); // Pour grouper les recherches de session
            $table->timestamp('search_timestamp')->useCurrent();
            $table->timestamps();
            
            $table->index(['user_id', 'search_timestamp']);
            $table->index('session_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_search_interactions');
    }
};
