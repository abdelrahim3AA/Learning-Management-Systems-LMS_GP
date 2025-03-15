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
        $student = new UserResource($this->student);
        $parent = new UserResource($this->parent);
        return [
            'id' => $this->id,
            'student' => $student->name,
            'parent' => $parent->name ?? null,
            'grade_level' => $this->grade_level,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
