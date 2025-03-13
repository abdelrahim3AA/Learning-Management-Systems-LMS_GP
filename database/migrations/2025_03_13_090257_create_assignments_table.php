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
        Schema::create('assignments', function (Blueprint $table) {
            $table->id(); // Standard Laravel 'id' instead of 'assignment_id'
            $table->foreignId('lesson_id')->constrained('lessons');
            $table->string('title');
            $table->text('description');
            $table->date('due_date');
            $table->timestamps(); // Using timestamps() instead of just created_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
