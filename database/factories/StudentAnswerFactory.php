<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\StudentAnswer;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentAnswerFactory extends Factory
{
    protected $model = StudentAnswer::class;

    public function definition(): array
    {
        $question = Question::inRandomOrder()->first() ?? Question::factory()->create();
        $option = QuestionOption::where('question_id', $question->id)->inRandomOrder()->first();

        return [
            'student_id' => Student::inRandomOrder()->first()->id ?? Student::factory(),
            'question_id' => $question->id,
            'selected_option_id' => $option ? $option->id : null,
            'essay_answer' => $this->faker->boolean(30) ? $this->faker->paragraph() : null, // 30% chance of being an essay answer
            'is_correct' => $this->faker->boolean(50), // 50% chance of being correct
        ];
    }
}
