<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Student::class;
    public function definition(): array
    {
        return [
            // Create a new user from type "Student Only"
            'student_id' => User::factory()->create(['role' => 'student'])->id,

            // Choise a random parent from prent's role only
            'parent_id' => User::where('role', 'parent')->inRandomOrder()->value('id'),

            'grade_level' => $this->faker->randomElement(['Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5']),
        ];
    }
}
