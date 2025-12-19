<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ScaleOption>
 */
class ScaleOptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->unique()->numberBetween(1, 999999),
            'scale_id' => $this->faker->numberBetween(1000, 999999),
            'label' => $this->faker->word(),
            'value' => $this->faker->numberBetween(1, 10),
            'order' => $this->faker->numberBetween(1, 10),
            'description' => $this->faker->optional()->sentence(),
        ];
    }
}
