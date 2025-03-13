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
        Schema::create('questions', function (Blueprint $table) {
            $table->id(); // Standard Laravel 'id' instead of 'question_id'
            $table->foreignId('lesson_id')->constrained('lessons');
            $table->text('question_text');
            $table->enum('question_type', ['mcq', 'checkbox', 'true_false', 'short_answer', 'essay']);
            $table->timestamps(); // Using timestamps() instead of just created_at
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
