<?php

namespace Database\Factories;

use App\Models\Assessment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<Assessment>
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

        Carbon::parse('2026-01-01');
        now();

        return [
            //            'user_id' => $this->faker->unique()->numberBetween(1000000000, 9999999999),
            //            'framework_id' => 1,
            //            'submitted_at' => $this->faker->optional(0.6)->dateTimeBetween($minDate, $now),
            //            'created_at' => $this->faker->dateTimeBetween($minDate, $now),
            //            'updated_at' => $this->faker->dateTimeBetween($minDate, $now),
        ];
    }
}
