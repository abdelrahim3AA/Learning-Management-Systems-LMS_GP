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
        Schema::create('parent_teacher_notifications', function (Blueprint $table) {
            $table->id(); // Standard Laravel 'id' instead of 'notification_id'
            $table->foreignId('recipient_id')->constrained('users');
            $table->foreignId('conversation_id')->constrained('parent_teacher_conversations');
            $table->string('message_preview');
            $table->boolean('is_read')->default(false);
            $table->timestamps(); // Using timestamps() instead of just created_at
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parent_teacher_notifications');
    }
};
