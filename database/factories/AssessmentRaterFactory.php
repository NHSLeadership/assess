<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AssessmentRater>
 */
class AssessmentRaterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'assessment_id' => $this->faker->unique()->numberBetween(1000, 999999),
            'rater_id' => $this->faker->unique()->numberBetween(1000, 999999),
            'role' => $this->faker->randomElement(['self','manager','direct_report','peer','other']),
        ];
    }
}
