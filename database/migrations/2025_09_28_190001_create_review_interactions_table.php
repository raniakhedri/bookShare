<?php
// database/migrations/2025_09_28_190001_create_review_interactions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('review_interactions', function (Blueprint $table) {
            $table->id('interaction_id');
            $table->foreignId('review_id')->constrained('reviews', 'review_id')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Interaction details
            $table->enum('interaction_type', ['reply', 'helpful_vote', 'unhelpful_vote', 'report', 'share', 'bookmark']);
            
            // Content (for replies and reports)
            $table->longText('content')->nullable();
            $table->foreignId('parent_interaction_id')->nullable()->constrained('review_interactions', 'interaction_id')->onDelete('cascade');
            
            // Analytics data
            $table->decimal('sentiment_score', 3, 2)->nullable(); // -1 to 1
            $table->decimal('quality_score', 3, 2)->nullable();   // 0 to 1
            $table->decimal('engagement_weight', 3, 2)->default(1.0);
            
            // Moderation
            $table->boolean('is_moderated')->default(false);
            $table->string('moderation_action', 50)->nullable();
            $table->foreignId('moderator_id')->nullable()->constrained('users')->onDelete('set null');
            
            // Context and metadata
            $table->json('context_data')->nullable();
            $table->unsignedTinyInteger('interaction_depth')->default(0);
            
            $table->timestamps();
            
            // Indexes for performance (with shorter names)
            $table->index(['review_id', 'interaction_type', 'created_at'], 'idx_review_inter_date');
            $table->index(['user_id', 'created_at'], 'idx_user_interactions');
            $table->index(['parent_interaction_id', 'interaction_depth'], 'idx_thread_struct');

            // Unique constraint to prevent duplicate votes
            $table->unique(['review_id', 'user_id', 'interaction_type'], 'unique_user_vote');
        });
    }

    public function down()
    {
        Schema::dropIfExists('review_interactions');
    }
};