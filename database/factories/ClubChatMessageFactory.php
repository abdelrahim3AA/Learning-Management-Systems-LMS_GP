<?php

namespace Database\Factories;

use App\Models\ClubChatMessage;
use App\Models\Club;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClubChatMessageFactory extends Factory
{
    protected $model = ClubChatMessage::class;

    public function definition(): array
    {
        return [
            'club_id' => Club::inRandomOrder()->first()->id ?? Club::factory(),
            'sender_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'message' => fake()->sentence(),
        ];
    }
}
