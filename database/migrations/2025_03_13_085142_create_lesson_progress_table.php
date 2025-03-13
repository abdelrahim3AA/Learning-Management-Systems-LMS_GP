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
        Schema::create('lesson_progress', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')
                  ->constrained('students')
                  ->cascadeOnDelete()
                  ->index();

            $table->foreignId('lesson_id')
                  ->constrained('lessons')
                  ->cascadeOnDelete()
                  ->index();

            $table->unsignedDecimal('progress_percentage', 5, 2)->default(0.00); 
            $table->enum('status', ['not_started', 'in_progress', 'completed'])->default('not_started');

            $table->timestamp('last_accessed')->useCurrent();
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lesson_progress');
    }
};
