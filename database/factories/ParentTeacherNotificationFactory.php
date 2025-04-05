<?php

namespace Database\Factories;

use App\Models\ParentTeacherNotification;
use App\Models\User;
use App\Models\ParentTeacherConversation;
use Illuminate\Database\Eloquent\Factories\Factory;

class ParentTeacherNotificationFactory extends Factory
{
    protected $model = ParentTeacherNotification::class;

    public function definition(): array
    {
        return [
            'recipient_id' => User::inRandomOrder()->first()->id,
            'conversation_id' => ParentTeacherConversation::inRandomOrder()->first()->id,
            'message_preview' => fake()->sentence(),
            'is_read' => fake()->boolean(),
        ];
    }
}
