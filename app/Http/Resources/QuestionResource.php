<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'lesson' => new LessonResource($this->whenLoaded('lesson')),
            'question_text' => $this->question_text,
            'question_type' => $this->question_type,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
