<?php

namespace Database\Factories;

use App\Models\Assessment;
use App\Models\Rater;
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
            'assessment_id' => Assessment::factory(),
            'rater_id'      => Rater::factory(),
            'role'          => $this->faker->randomElement([
                'self', 'manager', 'direct_report', 'peer', 'other'
            ]),
        ];
    }
}
