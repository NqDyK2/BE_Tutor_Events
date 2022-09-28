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
            'user_email' => $this->faker,
            'classroom_id',
            'school_teacher_id',
            'school_classroom',
            'reason'
        ];
    }
}
