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
        Schema::create('student_submissions', function (Blueprint $table) {
            $table->id(); // Standard Laravel 'id' instead of 'submission_id'
            $table->foreignId('student_id')->constrained('students');
            $table->foreignId('assignment_id')->constrained('assignments');
            $table->string('file_path')->nullable();
            $table->text('submission_text')->nullable();
            $table->timestamps(); // Using timestamps() instead of just submitted_at
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_submissions');
    }
};
