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
        Schema::create('club_chat_messages', function (Blueprint $table) {
            $table->id(); // Standard Laravel 'id' instead of 'message_id'
            $table->foreignId('club_id')->constrained('clubs');
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
        Schema::dropIfExists('club_chat_messages');
    }
};
