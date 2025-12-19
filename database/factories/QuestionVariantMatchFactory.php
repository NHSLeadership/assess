<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QuestionVariantMatch>
 */
class QuestionVariantMatchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'question_variant_id' => $this->faker->numberBetween(1000, 999999),
            'framework_variant_attribute_id' => $this->faker->numberBetween(1000, 999999),
            'framework_variant_option_id' => $this->faker->numberBetween(1000, 999999),
        ];
    }
}
