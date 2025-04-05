<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StudentAnswer;
use App\Models\Student;
use App\Models\Question;

class StudentAnswerSeeder extends Seeder
{
    public function run()
    {
        StudentAnswer::factory()->count(50)->create();
    }
}
