<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentSubmissionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'student_id' => $this->student_id,
            'assignment_id' => $this->assignment_id,
            'file_path' => $this->file_path ? url('storage/' . $this->file_path) : null,
            'submission_text' => $this->submission_text,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
