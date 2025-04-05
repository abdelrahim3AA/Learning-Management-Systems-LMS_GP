<?php

namespace Database\Seeders;

use App\Models\LessonProgress;
use Illuminate\Database\Seeder;

class LessonProgressSeeder extends Seeder
{
    public function run()
    {
        LessonProgress::factory()->count(20)->create();
    }
}
