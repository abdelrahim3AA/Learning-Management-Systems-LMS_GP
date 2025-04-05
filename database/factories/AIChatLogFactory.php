<?php

namespace Database\Factories;

use App\Models\AIChatLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AIChatLogFactory extends Factory
{
    protected $model = AIChatLog::class;

    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'message' => $this->faker->text(),
        ];
    }
}
