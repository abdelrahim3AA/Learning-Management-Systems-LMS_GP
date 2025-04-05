<?php

namespace Database\Factories;

use App\Models\LessonProgress;
use App\Models\Student;
use App\Models\Lesson;
use Illuminate\Database\Eloquent\Factories\Factory;

class LessonProgressFactory extends Factory
{
    protected $model = LessonProgress::class;

    public function definition()
    {
        return [
            'student_id' => Student::factory(),
            'lesson_id' => Lesson::factory(),
            'progress_percentage' => $this->faker->randomFloat(2, 0, 100),
            'status' => $this->faker->randomElement(['not_started', 'in_progress', 'completed']),
            'last_accessed' => now(),
            'completed_at' => $this->faker->optional()->dateTime(),
        ];
    }
}
