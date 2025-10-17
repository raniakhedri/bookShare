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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id('review_id');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('book_id')->constrained('books')->onDelete('cascade');
            $table->decimal('overall_rating', 3, 1)->nullable();
            $table->decimal('content_rating', 3, 1)->nullable();
            $table->decimal('condition_rating', 3, 1)->nullable();
            $table->decimal('recommendation_level', 3, 1)->nullable();
            $table->decimal('difficulty_level', 3, 1)->nullable();
            $table->string('review_title')->nullable();
            $table->text('review_text');
            $table->string('reading_context')->nullable();
            $table->boolean('is_spoiler')->default(false);
            $table->text('content_warnings')->nullable();
            $table->json('photo_urls')->nullable();
            $table->string('status')->default('active');
            $table->integer('helpful_votes')->default(0);
            $table->integer('unhelpful_votes')->default(0);
            $table->integer('reply_count')->default(0);
            $table->integer('view_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
