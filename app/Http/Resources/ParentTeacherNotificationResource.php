<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ParentTeacherNotificationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'recipient' => [
                'id' => $this->recipient->id,
                'name' => $this->recipient->name,
            ],
            'conversation' => [
                'id' => $this->conversation->id,
                'parent' => $this->conversation->parent->name,
                'teacher' => $this->conversation->teacher->name,
            ],
            'message_preview' => $this->message_preview,
            'is_read' => $this->is_read,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
