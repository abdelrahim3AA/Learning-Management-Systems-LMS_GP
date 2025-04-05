<?php

namespace Database\Factories;

use App\Models\Exam;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExamResultFactory extends Factory
{
    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'exam_id' => Exam::factory(),
            'score' => fake()->randomFloat(2, 0, 100),
            'total_marks' => 100,
        ];
    }
}
