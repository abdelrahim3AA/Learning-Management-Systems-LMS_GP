<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClubChatMessageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'club' => [
                'id' => $this->club->id,
                'name' => $this->club->name,
            ],
            'sender' => [
                'id' => $this->sender->id,
                'name' => $this->sender->name,
            ],
            'message' => $this->message,
            'sent_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
