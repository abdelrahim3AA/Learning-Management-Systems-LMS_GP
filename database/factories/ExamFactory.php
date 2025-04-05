<?php

namespace Database\Factories;

use App\Models\Exam;
use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ExamFactory extends Factory
{
    protected $model = Exam::class;

    public function definition()
    {
        return [
            'course_id' => Course::factory(), // Generate a random course
            'title' => fake()->sentence, // Random exam title
            'exam_date' => fake()->dateTimeThisYear, // Random exam date
        ];
    }
}
