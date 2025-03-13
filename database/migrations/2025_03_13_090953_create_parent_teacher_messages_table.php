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
        Schema::create('parent_teacher_messages', function (Blueprint $table) {
            $table->id(); // Standard Laravel 'id' instead of 'message_id'
            $table->foreignId('conversation_id')->constrained('parent_teacher_conversations');
            $table->foreignId('sender_id')->constrained('users');
            $table->text('message');
            $table->timestamps(); // Using timestamps() instead of just sent_at
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parent_teacher_messages');
    }
};
