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

            // Explicitly define constraint name for user_id
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // Use a completely different approach for the second foreign key
            // to avoid any possibility of duplicate constraint names
            $table->unsignedBigInteger('parent_id')->nullable()->index();
            // Add the constraint with an explicit name
            $table->foreign('parent_id', 'students_parent_user_foreign')
                  ->references('id')
                  ->on('users')
                  ->nullOnDelete();

            $table->string('grade_level', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
