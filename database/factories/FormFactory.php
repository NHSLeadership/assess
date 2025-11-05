<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class FormFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'assessment_id' => $this->faker->randomElement([1, 2]),
            'area_id' => $this->faker->randomElement([1, 3]),
            'slug' => $this->faker->slug(),
            'name' => $this->faker->sentence(1),
            'description' => $this->faker->sentence(3),
        ];
    }
}
