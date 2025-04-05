<?php

namespace Database\Factories;

use App\Models\Question;
use App\Models\Lesson;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionFactory extends Factory
{
    protected $model = Question::class;

    public function definition(): array
    {
        return [
            'lesson_id' => Lesson::inRandomOrder()->first()->id ?? Lesson::factory(),
            'question_text' => fake()->sentence(),
            'question_type' => fake()->randomElement(['mcq', 'checkbox', 'true_false', 'short_answer', 'essay']),
        ];
    }
}
