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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_participants');
    }
};
