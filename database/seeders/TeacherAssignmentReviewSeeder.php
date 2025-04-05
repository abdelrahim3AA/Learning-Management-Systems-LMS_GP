<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TeacherAssignmentReview;

class TeacherAssignmentReviewSeeder extends Seeder
{
    public function run()
    {
        TeacherAssignmentReview::factory()->count(50)->create();
    }
}
