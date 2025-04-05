<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exam;

class ExamSeeder extends Seeder
{
    public function run(): void
    {
        Exam::create([
            'course_id' => 1, // Make sure this course exists
            'title' => 'Midterm Exam',
            'exam_date' => now()->addDays(10),
        ]);

        Exam::create([
            'course_id' => 2,
            'title' => 'Final Exam',
            'exam_date' => now()->addDays(30),
        ]);
    }
}
