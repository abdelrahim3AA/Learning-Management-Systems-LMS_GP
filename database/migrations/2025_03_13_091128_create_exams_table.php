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
        Schema::create('exams', function (Blueprint $table) {
            $table->id(); // Standard Laravel 'id' instead of 'exam_id'
            $table->foreignId('course_id')->constrained('courses');
            $table->string('title');
            $table->dateTime('exam_date');
            $table->timestamps(); // Using timestamps() instead of just created_at
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
