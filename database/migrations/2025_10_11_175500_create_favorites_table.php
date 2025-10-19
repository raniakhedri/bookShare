<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (!Schema::hasTable('favorites')) {
            Schema::create('favorites', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('book_id')->constrained()->onDelete('cascade');
                $table->timestamps();

                // Unique constraint to prevent duplicate favorites
                $table->unique(['user_id', 'book_id']);

                // Index for faster queries
                $table->index(['user_id', 'created_at']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('favorites');
    }
};