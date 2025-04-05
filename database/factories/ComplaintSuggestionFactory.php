<?php

namespace Database\Factories;

use App\Models\ComplaintSuggestion;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ComplaintSuggestionFactory extends Factory
{
    protected $model = ComplaintSuggestion::class;

    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'type' => $this->faker->randomElement(['complaint', 'suggestion']),
            'message' => $this->faker->text(),
            'status' => $this->faker->randomElement(['pending', 'resolved']),
        ];
    }
}
