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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id(); // Standard Laravel 'id' instead of 'teacher_id'
            $table->foreignId('user_id')->unique()->constrained('users');
            $table->text('qualification')->nullable();
            $table->text('bio')->nullable();
            $table->timestamps(); // Adding timestamps for consistency
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
