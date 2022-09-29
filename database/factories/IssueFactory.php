<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Issue>
 */
class IssueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'lesson_id' => rand(1,50),
            'content' => $this->faker->text($maxNbChars = 200),
            'is_resolved' => rand(0,5),
        ];
    }
}
