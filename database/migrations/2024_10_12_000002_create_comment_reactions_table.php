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
        Schema::create('comment_reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comment_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('reaction_type', [
                'like',        // 👍 J'aime
                'love',        // ❤️ J'adore
                'laugh',       // 😂 Drôle
                'wow',         // 😮 Surprenant
                'sad',         // 😢 Triste
                'angry',       // 😠 En colère
                'celebrate'    // 🎉 Célébrer
            ])->default('like');
            $table->timestamps();
            
            // Un utilisateur ne peut avoir qu'une seule réaction par commentaire
            $table->unique(['comment_id', 'user_id']);
            
            // Index pour les requêtes fréquentes
            $table->index(['comment_id', 'reaction_type']);
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comment_reactions');
    }
};