<?php

namespace App\Events;

use App\Models\AiChatLog;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AiChatMessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $chatMessage;

    public function __construct(AiChatLog $chatMessage)
    {
        $this->chatMessage = $chatMessage;
    }

    public function broadcastOn()
    {
        return new Channel('ai-chat-channel');
    }

    public function broadcastWith()
    {
        return [
            'message' => $this->chatMessage->message,
            'user_id' => $this->chatMessage->user_id,
            'created_at' => $this->chatMessage->created_at->toDateTimeString(),
        ];
    }
}
