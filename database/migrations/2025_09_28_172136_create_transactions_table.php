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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marketbook_id')->constrained('market_books')->onDelete('cascade');
            $table->foreignId('requester_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['pending', 'accepted', 'rejected', 'completed'])->default('pending');
            $table->enum('type', ['gift', 'exchange']);
            $table->text('message')->nullable(); // Optional message from requester
            $table->timestamp('responded_at')->nullable();
            $table->text('response_message')->nullable(); // Optional response from owner
            $table->timestamps();

            $table->index(['marketbook_id', 'status']);
            $table->index(['requester_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
