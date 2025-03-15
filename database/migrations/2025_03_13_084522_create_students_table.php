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
        Schema::create('students', function (Blueprint $table) {
            $table->id();

            // Ensure only students can be added
            $table->foreignId('student_id')
            ->constrained('users')
            ->cascadeOnDelete()
            ->unique(); // Prevents duplicate students

            $table->unsignedBigInteger('parent_id')->nullable()->index();
            $table->foreign('parent_id', 'students_parent_user_foreign')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->string('grade_level', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
