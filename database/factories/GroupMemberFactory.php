<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\GroupMember;
use App\Models\Student;
use App\Models\Group;

class GroupMemberFactory extends Factory
{
    protected $model = GroupMember::class;

    public function definition(): array
    {
        return [
            'student_id' => Student::inRandomOrder()->first()->id ?? Student::factory(),
            'group_id' => Group::inRandomOrder()->first()->id ?? Group::factory(),
        ];
    }
}
