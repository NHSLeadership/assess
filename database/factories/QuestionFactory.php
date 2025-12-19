<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
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
            'node_id' => $this->faker->unique()->numberBetween(1000, 999999),
            'title' => $this->faker->title(),
            'text' => $this->faker->text(),
            'response_type' => $this->faker->randomElement(['single_choice','multi_choice','scale','boolean','textarea']),
            'required' => $this->faker->boolean(),
            'order' => $this->faker->numberBetween(1, 100),
        ];
    }
}
