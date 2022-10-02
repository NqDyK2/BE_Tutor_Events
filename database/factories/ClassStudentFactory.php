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
            'user_email' => $this->faker->email(),
            'classroom_id' => rand(1,30),
            'school_teacher_id' => rand(1,30),
            'school_classroom'=> rand(1,30),
            'reason' => $this->faker->catchPhrase()
        ];
    }
}
