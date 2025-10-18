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
        Schema::create('review_interactions', function (Blueprint $table) {
            $table->id('interaction_id');
            $table->foreignId('review_id')->constrained('reviews', 'review_id')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('interaction_type');
            $table->text('content')->nullable();
            $table->unsignedBigInteger('parent_interaction_id')->nullable();
            $table->decimal('sentiment_score', 5, 2)->nullable();
            $table->decimal('quality_score', 5, 2)->nullable();
            $table->decimal('engagement_weight', 5, 2)->nullable();
            $table->json('context_data')->nullable();
            $table->integer('interaction_depth')->default(0);
            $table->boolean('is_moderated')->default(false);
            $table->foreignId('moderator_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            // Self-referential foreign key for parent-child relationships
            $table->foreign('parent_interaction_id')
                  ->references('interaction_id')
                  ->on('review_interactions')
                  ->onDelete('cascade');

            // Indexes
            $table->index(['review_id', 'interaction_type']);
            $table->index(['parent_interaction_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('review_interactions');
    }
};
