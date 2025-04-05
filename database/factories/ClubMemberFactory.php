<?php

namespace Database\Factories;

use App\Models\ClubMember;
use App\Models\Student;
use App\Models\Club;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClubMemberFactory extends Factory
{
    protected $model = ClubMember::class;

    public function definition(): array
    {
        return [
            'student_id' => Student::inRandomOrder()->first()->id ?? Student::factory(),
            'club_id' => Club::inRandomOrder()->first()->id ?? Club::factory(),
        ];
    }
}
