<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\Assignment;
use App\Models\StudentSubmission;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentSubmissionFactory extends Factory
{
    protected $model = StudentSubmission::class;

    public function definition(): array
    {
        return [
            'student_id' => Student::inRandomOrder()->first()->id ?? Student::factory(),
            'assignment_id' => Assignment::inRandomOrder()->first()->id ?? Assignment::factory(),
            'file_path' => fake()->boolean(50) ? 'submissions/' . fake()->uuid . '.pdf' : null,
            'submission_text' => fake()->boolean(50) ? fake()->paragraph() : null,
        ];
    }
}
