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
        Schema::create('group_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('groups')->onDelete('cascade');
            $table->foreignId('creator_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->enum('type', ['meeting', 'reading_club', 'challenge', 'discussion', 'workshop', 'social', 'other']);
            $table->string('location')->nullable(); // Lieu physique ou lien virtuel
            $table->boolean('is_virtual')->default(false);
            $table->string('meeting_link')->nullable(); // Lien Zoom, Teams, etc.
            $table->datetime('start_datetime');
            $table->datetime('end_datetime');
            $table->integer('max_participants')->nullable(); // Limite de participants
            $table->boolean('requires_approval')->default(false); // Inscription soumise à approbation
            $table->enum('status', ['draft', 'published', 'ongoing', 'completed', 'cancelled'])->default('draft');
            $table->json('resources')->nullable(); // Ressources nécessaires (livres, matériel, etc.)
            $table->string('cover_image')->nullable();
            $table->text('requirements')->nullable(); // Prérequis pour participer
            $table->timestamps();
            
            // Index pour optimiser les requêtes
            $table->index(['group_id', 'status']);
            $table->index(['start_datetime', 'status']);
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_events');
    }
};
