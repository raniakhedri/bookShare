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
        Schema::create('group_badges', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // "Lecteur Assidu", "Critique Expert"
            $table->string('slug')->unique(); // "lecteur-assidu"
            $table->text('description');
            $table->string('icon')->nullable(); // Path vers l'icône
            $table->string('color', 7)->default('#3B82F6'); // Couleur hex
            $table->enum('type', [
                'participation',  // Badges de participation
                'quality',       // Badges de qualité
                'achievement',   // Badges d'accomplissement
                'special',       // Badges spéciaux
                'milestone'      // Badges de jalons
            ]);
            $table->json('criteria'); // Critères pour obtenir le badge
            $table->boolean('is_active')->default(true);
            $table->integer('points_awarded')->default(0); // Points gagnés
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_badges');
    }
};