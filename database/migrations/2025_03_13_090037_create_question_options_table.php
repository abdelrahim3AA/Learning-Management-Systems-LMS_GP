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
        Schema::create('question_options', function (Blueprint $table) {
            $table->id(); // Standard Laravel 'id' instead of 'option_id'
            $table->foreignId('question_id')->constrained('questions');
            $table->text('option_text');
            $table->boolean('is_correct');
            $table->timestamps(); // Adding timestamps for consistency
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_options');
    }
};
