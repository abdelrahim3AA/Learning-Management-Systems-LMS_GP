<?php

namespace Database\Factories;

use App\Models\ParentTeacherMessage;
use App\Models\ParentTeacherConversation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ParentTeacherMessageFactory extends Factory
{
    protected $model = ParentTeacherMessage::class;

    public function definition(): array
    {
        return [
            'conversation_id' => ParentTeacherConversation::inRandomOrder()->first()->id,
            'sender_id' => User::inRandomOrder()->first()->id,
            'message' => fake()->text(),
        ];
    }
}
