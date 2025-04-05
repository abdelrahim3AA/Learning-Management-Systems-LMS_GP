<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\Lesson;

class QuestionSeeder extends Seeder
{
    public function run()
    {
        $lessons = Lesson::all();

        foreach ($lessons as $lesson) {
            Question::factory()->count(5)->create([
                'lesson_id' => $lesson->id,
            ]);
        }
    }
}
