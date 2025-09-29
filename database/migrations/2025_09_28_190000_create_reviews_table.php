<?php
// database/migrations/2025_09_28_190000_create_reviews_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id('review_id');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            
            // Multi-dimensional ratings
            $table->decimal('overall_rating', 2, 1)->nullable();
            $table->decimal('content_rating', 2, 1)->nullable();
            $table->decimal('condition_rating', 2, 1)->nullable();
            $table->decimal('recommendation_level', 2, 1)->nullable();
            $table->decimal('difficulty_level', 2, 1)->nullable();
            
            // Review content
            $table->string('review_title', 200)->nullable();
            $table->longText('review_text')->nullable();
            $table->text('reading_context')->nullable();
            
            // Metadata
            $table->boolean('is_spoiler')->default(false);
            $table->string('content_warnings')->nullable();
            
            // Community interaction counters
            $table->unsignedInteger('helpful_votes')->default(0);
            $table->unsignedInteger('unhelpful_votes')->default(0);
            $table->unsignedInteger('reply_count')->default(0);
            $table->unsignedInteger('view_count')->default(0);
            
            // Moderation
            $table->enum('status', ['active', 'pending', 'flagged', 'removed'])->default('active');
            $table->text('moderation_notes')->nullable();
            
            // Photos/attachments (JSON column)
            $table->json('photo_urls')->nullable();
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['book_id', 'overall_rating']);
            $table->index(['user_id', 'created_at']);
            $table->index(['helpful_votes', 'created_at']);
            $table->index('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
    }
};