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
        Schema::create('club_members', function (Blueprint $table) {
            $table->id(); // Standard Laravel 'id' instead of 'member_id'
            $table->foreignId('student_id')->constrained('students');
            $table->foreignId('club_id')->constrained('clubs');
            $table->timestamps(); // Adding timestamps for consistency
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('club_members');
    }
};
