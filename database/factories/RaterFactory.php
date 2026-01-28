<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rater>
 */
class RaterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->unique()->numberBetween(1000, 999999), // if your PK is UUID
            'user_id' => $this->faker->unique()->numberBetween(1000, 999999),
            'email' => $this->faker->unique()->safeEmail(),
            'name' => $this->faker->name(),
        ];
    }
}
