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
        // Créer la table group_events
        if (!Schema::hasTable('group_events')) {
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

        // Créer la table event_participants
        if (!Schema::hasTable('event_participants')) {
            Schema::create('event_participants', function (Blueprint $table) {
                $table->id();
                $table->foreignId('event_id')->constrained('group_events')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->enum('status', ['pending', 'approved', 'rejected', 'confirmed', 'attended', 'absent'])->default('pending');
                $table->datetime('registered_at');
                $table->datetime('approved_at')->nullable();
                $table->text('registration_message')->nullable(); // Message lors de l'inscription
                $table->text('rejection_reason')->nullable(); // Raison du refus
                $table->boolean('reminded')->default(false); // Rappel envoyé
                $table->json('additional_info')->nullable(); // Infos supplémentaires (régime alimentaire, etc.)
                $table->timestamps();
                
                // Éviter les doublons
                $table->unique(['event_id', 'user_id']);
                
                // Index pour optimiser les requêtes
                $table->index(['event_id', 'status']);
                $table->index('registered_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_participants');
        Schema::dropIfExists('group_events');
    }
};