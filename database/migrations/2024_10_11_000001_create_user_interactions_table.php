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
        Schema::create('user_interactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->enum('interaction_type', [
                'view', 'like', 'share', 'download', 'read_time',
                'search', 'rate', 'comment', 'bookmark', 'wishlist'
            ]);
            $table->decimal('interaction_value', 8, 2)->default(1.00);
            $table->integer('duration_seconds')->nullable();
            $table->json('context_data')->nullable();
            $table->timestamp('timestamp')->useCurrent();
            $table->timestamps();

            // Index pour optimiser les requêtes
            $table->index(['user_id', 'timestamp']);
            $table->index(['book_id', 'interaction_type']);
            $table->index(['interaction_type', 'timestamp']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_interactions');
    }
};