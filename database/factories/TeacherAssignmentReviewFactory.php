<?php

namespace Database\Factories;

use App\Models\Teacher;
use App\Models\StudentSubmission;
use App\Models\TeacherAssignmentReview;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeacherAssignmentReviewFactory extends Factory
{
    protected $model = TeacherAssignmentReview::class;

    public function definition(): array
    {
        return [
            'submission_id' => StudentSubmission::inRandomOrder()->first()->id ?? StudentSubmission::factory(),
            'teacher_id' => Teacher::inRandomOrder()->first()->id ?? Teacher::factory(),
            'feedback' => fake()->paragraph(),
            'score' => fake()->randomFloat(2, 0, 100), // من 0 إلى 100
        ];
    }
}
