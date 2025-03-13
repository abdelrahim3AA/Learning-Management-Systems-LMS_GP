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
        Schema::create('student_answers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')
                  ->constrained('students')
                  ->cascadeOnDelete()
                  ->index();

            $table->foreignId('question_id')
                  ->constrained('questions')
                  ->cascadeOnDelete()
                  ->index();

            $table->foreignId('selected_option_id')
                  ->nullable()
                  ->constrained('question_options')
                  ->cascadeOnDelete()
                  ->index();

            $table->text('essay_answer')->nullable();

            $table->boolean('is_correct')->default(false);

            $table->timestamp('answered_at')->useCurrent();

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_answers');
    }
};
