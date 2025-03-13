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
        Schema::create('course_enrollments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')
                  ->constrained('students')
                  ->cascadeOnDelete()
                  ->index(); 

            $table->foreignId('course_id')
                  ->constrained('courses')
                  ->cascadeOnDelete()
                  ->index(); 

            $table->timestamp('enrolled_at')->useCurrent();

            $table->unique(['student_id', 'course_id']); // منع تسجيل الطالب في نفس الكورس أكثر من مرة
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_enrollments');
    }
};
