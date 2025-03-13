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
        Schema::create('groups', function (Blueprint $table) {
            $table->id(); // Standard Laravel 'id' instead of 'group_id'
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('teacher_id')->constrained('teachers');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->timestamps(); // Adding timestamps for consistency
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('groups');
    }
};
