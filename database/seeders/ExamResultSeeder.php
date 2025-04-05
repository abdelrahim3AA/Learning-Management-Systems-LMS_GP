<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExamResult;

class ExamResultSeeder extends Seeder
{
    public function run(): void
    {
        ExamResult::factory()->count(20)->create();
    }
}
