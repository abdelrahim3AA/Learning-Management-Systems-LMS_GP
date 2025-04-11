<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AIChatLogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => new UserResource($this->whenLoaded('user')),
            'message' => $this->message,
            'is_ai' => $this->is_ai,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
