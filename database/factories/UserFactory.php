<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'google_id' => $this->faker->uuid(),
            'name' => $this->faker->name(),
            'status' => rand(0,1),
            'user_code' => Str::random(7),
            'email' => $this->faker->email(),
            'avatar' => $this->faker->imageUrl(100,150),
            'gender' => rand(0,1),
            'address' => $this->faker->address(),
            'phone_number' => $this->faker->phoneNumber(),
            'dob' => $this->faker->date(),
            'role_id' => rand(0,3),
            'major_id' => rand(1,20)
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
