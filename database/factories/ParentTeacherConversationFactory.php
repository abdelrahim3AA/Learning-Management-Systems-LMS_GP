<?php

namespace Database\Factories;

use App\Models\ParentTeacherConversation;
use App\Models\User;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

class ParentTeacherConversationFactory extends Factory
{
    protected $model = ParentTeacherConversation::class;

    public function definition(): array
    {
        return [
            'parent_id' => User::where('role', 'parent')->inRandomOrder()->first()->id ?? User::factory(),
            'teacher_id' => Teacher::inRandomOrder()->first()->id ?? Teacher::factory(),
        ];
    }
}
