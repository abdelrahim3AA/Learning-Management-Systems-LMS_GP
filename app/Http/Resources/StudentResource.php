<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'student_id' => $this->student_id,
            'parent_id' => $this->parent_id,
            'grade_level' => $this->grade_level,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // Include related data when available
            'user' => $this->whenLoaded('user'),
            'parent' => $this->whenLoaded('parent'),
            'enrollments' => $this->whenLoaded('enrollments'),
            'lesson_progress' => $this->whenLoaded('lessonProgress'),
        ];
    }
}