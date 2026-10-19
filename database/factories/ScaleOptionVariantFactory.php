<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\ScaleOptionVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ScaleOptionVariant>
 */
class ScaleOptionVariantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'scale_option_id' => $this->faker->numberBetween(1000, 999999),
            'text' => $this->faker->sentence(),
            'audience' => $this->faker->optional()->randomElement(['self', 'rater']),
            'priority' => $this->faker->numberBetween(0, 10),
        ];
    }
}

