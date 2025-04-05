<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamResultResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'score' => $this->score,
            'total_marks' => $this->total_marks,

            'student' => new StudentResource($this->whenLoaded('student')),
            'exam' => new ExamResource($this->whenLoaded('exam')),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
