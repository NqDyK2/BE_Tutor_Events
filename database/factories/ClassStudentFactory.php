<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClassStudent>
 */
class ClassStudentFactory extends Factory
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
            'classroom_id' => rand(1,30),
            'reason' => $this->faker->catchPhrase(),
            'final_result' => rand(0,9)
        ];
    }
}
