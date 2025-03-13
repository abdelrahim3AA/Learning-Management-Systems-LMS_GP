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
        Schema::create('complaints_suggestions', function (Blueprint $table) {
            $table->id(); // Standard Laravel 'id' instead of 'complaint_id'
            $table->foreignId('user_id')->constrained('users');
            $table->enum('type', ['complaint', 'suggestion']);
            $table->text('message');
            $table->enum('status', ['pending', 'resolved'])->default('pending');
            $table->timestamps(); // Using timestamps() instead of just submitted_at
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints_suggestions');
    }
};
