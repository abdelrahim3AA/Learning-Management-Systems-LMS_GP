<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StudentSubmission;

class StudentSubmissionSeeder extends Seeder
{
    public function run()
    {
        StudentSubmission::factory()->count(50)->create();
    }
}
