<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Classroom>
 */
class ClassroomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'user_id' => rand(1,30),
            'subject_id' => rand(1,30),
            'semester_id' => rand(1,30),
            'default_online_class_location' => $this->faker->secondaryAddress(),
            'default_offline_class_location' => $this->faker->url(),
            'default_tutor_email' => $this->faker->email()
        ];
    }
}
