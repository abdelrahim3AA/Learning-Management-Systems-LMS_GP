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
        Schema::create('teacher_assignment_review', function (Blueprint $table) {
            $table->id(); // Standard Laravel 'id' instead of 'review_id'
            $table->foreignId('submission_id')->constrained('student_submissions');
            $table->foreignId('teacher_id')->constrained('teachers');
            $table->text('feedback');
            $table->float('score');
            $table->timestamps(); // Using timestamps() instead of just reviewed_at
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
