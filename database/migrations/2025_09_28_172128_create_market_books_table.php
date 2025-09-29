<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('market_books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('author');
            $table->text('description')->nullable();
            $table->enum('condition', ['New', 'Good', 'Fair', 'Poor'])->default('Good');
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->string('image')->nullable();
            $table->decimal('price', 10, 2)->nullable(); // Optional price for reference
            $table->boolean('is_available')->default(true);
            $table->timestamps();

            $table->index(['owner_id', 'is_available']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('market_books');
    }
};
