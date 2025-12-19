<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Response>
 */
class ResponseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'question_id' => $this->faker->numberBetween(1000, 999999),
            'assessment_id' => $this->faker->numberBetween(1000, 999999),
            'rater_id' => $this->faker->numberBetween(1000, 999999),
            'scale_option_id' => $this->faker->optional()->numberBetween(1000, 999999),
            'textarea' => $this->faker->optional()->paragraph(),
        ];
    }
}
