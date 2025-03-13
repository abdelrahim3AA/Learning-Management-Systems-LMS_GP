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
        Schema::create('teacher_assignment_reviews', function (Blueprint $table) {
            $table->id();

            $table->foreignId('submission_id')
                  ->constrained('student_submissions')
                  ->cascadeOnDelete()
                  ->index();

            $table->foreignId('teacher_id')
                  ->constrained('teachers')
                  ->cascadeOnDelete()
                  ->index();

            $table->text('feedback');
            $table->decimal('score', 5, 2);

            $table->timestamps(); 
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_assignment_review');
    }
};
