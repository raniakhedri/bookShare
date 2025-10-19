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
        if (!Schema::hasTable('books')) {
            Schema::create('books', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('author');
                $table->text('description')->nullable();
                $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
                $table->enum('condition', ['New', 'Good', 'Used'])->default('Good');
                $table->boolean('availability')->default(true);
                $table->timestamp('publication_year')->nullable();
                $table->string('image')->nullable();
                $table->string('file')->nullable(); // chemin du PDF ou audio
                $table->enum('type', ['pdf', 'audio'])->nullable();
                $table->foreignId('user_id')->constrained()->onDelete('cascade'); // who added the book
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
