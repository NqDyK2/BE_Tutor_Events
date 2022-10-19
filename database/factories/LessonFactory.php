<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lesson>
 */
class LessonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'classroom_id' => rand(1,30),
            'type' => rand(0,5),
            'content' => $this->faker->secondaryAddress(),
            'teacher_email' => $this->faker->email(),
            'tutor_email' => $this->faker->email(),
            'start_time' => $this->faker->date(),
            'end_time' => $this->faker->date(),
            'class_location_online' => $this->faker->url(),
            'class_location_offline' => $this->faker->secondaryAddress(),
        ];
    }
}
