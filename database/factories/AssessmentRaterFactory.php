<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\RaterType;
use App\Models\Assessment;
use App\Models\AssessmentRater;
use App\Models\Rater;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AssessmentRater>
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
            'rater_id' => Rater::factory(),
            'type' => $this->faker->randomElement(RaterType::cases())->value,
        ];
    }
}
