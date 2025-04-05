<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GroupMember;
use App\Models\Student;
use App\Models\Group;

class GroupMemberSeeder extends Seeder
{
    public function run()
    {
        $students = Student::pluck('id')->toArray();
        $groups = Group::pluck('id')->toArray();

        foreach ($groups as $group) {
            GroupMember::factory()->count(rand(3, 10))->create([
                'group_id' => $group,
                'student_id' => $students[array_rand($students)],
            ]);
        }
    }
}
