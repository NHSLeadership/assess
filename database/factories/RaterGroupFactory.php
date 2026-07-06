<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\RaterGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RaterGroup>
 */
class RaterGroupFactory extends Factory
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
            'subject_id' => $this->faker->numberBetween(1000, 999999),
            'name' => $this->faker->words(2, true),
        ];
    }
}
