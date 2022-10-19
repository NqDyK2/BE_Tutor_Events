<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attendance>
 */
class AttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'student_email' => $this->faker->email(),
            'lesson_id' => rand(1,50),
            'note' => $this->faker->text($maxNbChars = 200),
            'status' => rand(1,10)

        ];
    }
}
