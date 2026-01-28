<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Assessment>
 */
class AssessmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $minDate = Carbon::parse('2026-01-01');
        $now     = now();

        return [
            'user_id' => $this->faker->unique()->numberBetween(1000, 999999),
            'framework_id' => 1,
            'submitted_at' => $this->faker->optional(0.6)->dateTimeBetween($minDate, $now),
            'created_at' => $this->faker->dateTimeBetween($minDate, $now),
            'updated_at' => $this->faker->dateTimeBetween($minDate, $now),
        ];
    }
}
