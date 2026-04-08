<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\ScaleOption;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ScaleOption>
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
