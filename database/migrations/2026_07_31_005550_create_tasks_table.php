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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();

            // Relationship
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            // Task details
            $table->string('title');
            $table->text('description')->nullable();

            // Task state
            $table->enum('status', [
                'todo',
                'in_progress',
                'done'
            ])->default('todo');

            // Optional priority
            $table->enum('priority', [
                'low',
                'medium',
                'high'
            ])->default('medium');

            // Optional dates
            $table->date('due_date')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
