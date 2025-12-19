<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Signpost>
 */
class SignpostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'framework_variant_option_id' => $this->faker->numberBetween(1000, 999999),
            'node_id' => $this->faker->numberBetween(1000, 999999),
            'min_value' => $this->faker->numberBetween(1, 50),
            'max_value' => $this->faker->numberBetween(51, 100),
            'guidance' => $this->faker->text(),
        ];
    }
}
