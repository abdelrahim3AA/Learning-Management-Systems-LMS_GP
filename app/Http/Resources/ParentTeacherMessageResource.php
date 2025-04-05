<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ParentTeacherMessageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'conversation' => [
                'id' => $this->conversation->id,
                'parent' => $this->conversation->parent->name,
                'teacher' => $this->conversation->teacher->name,
            ],
            'sender' => [
                'id' => $this->sender->id,
                'name' => $this->sender->name,
            ],
            'message' => $this->message,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
