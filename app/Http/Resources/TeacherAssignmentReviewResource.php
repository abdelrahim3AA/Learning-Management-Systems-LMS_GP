<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherAssignmentReviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'submission_id' => $this->submission_id,
            'teacher_id' => $this->teacher_id,
            'feedback' => $this->feedback,
            'score' => $this->score,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
