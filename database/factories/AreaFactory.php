<?php

namespace Database\Factories;

use App\Models\Framework;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Area>
 */
class AreaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'framework_id' => Framework::factory(),
            'parent_id' => null,
            'slug' => $this->faker->unique()->slug(),
            'name' => $this->faker->sentence(1),
            'description' => $this->faker->sentence(3),
            'colour' => $this->faker->randomElement(['blue', 'green', 'orange']),
        ];
    }
}
