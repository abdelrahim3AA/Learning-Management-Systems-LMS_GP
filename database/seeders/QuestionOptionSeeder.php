<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\QuestionOption;
use App\Models\Question;

class QuestionOptionSeeder extends Seeder
{
    public function run()
    {
        $questions = Question::all();

        foreach ($questions as $question) {
            QuestionOption::factory()->count(4)->create([
                'question_id' => $question->id,
            ]);
        }
    }
}
