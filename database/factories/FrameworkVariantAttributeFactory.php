<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FrameworkVariantAttribute>
 */
class FrameworkVariantAttributeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->unique()->numberBetween(1000, 999999),
            'framework_id' => $this->faker->numberBetween(1000, 999999),
            'key' => $this->faker->word(),
            'label' => $this->faker->title(),
            'hint_text' => $this->faker->sentence(),
            'order' => $this->faker->numberBetween(1, 100),
        ];
    }
}
