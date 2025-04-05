<?php

namespace Database\Factories;

use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionOptionFactory extends Factory
{
    protected $model = QuestionOption::class;

    public function definition(): array
    {
        return [
            'question_id' => Question::inRandomOrder()->first()->id ?? Question::factory(),
            'option_text' => $this->faker->sentence(5),
            'is_correct' => $this->faker->boolean(20), // 20% chance of being correct
        ];
    }
}
