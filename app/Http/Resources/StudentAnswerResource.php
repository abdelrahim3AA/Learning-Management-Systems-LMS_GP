<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentAnswerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'student_id' => $this->student_id,
            'question_id' => $this->question_id,
            'selected_option_id' => $this->selected_option_id,
            'essay_answer' => $this->essay_answer,
            'is_correct' => $this->is_correct,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
