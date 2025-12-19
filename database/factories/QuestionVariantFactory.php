<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QuestionVariant>
 */
class QuestionVariantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'question_id' => $this->faker->numberBetween(1000, 999999),
            'text' => $this->faker->text(),
            'priority' => $this->faker->numberBetween(1, 10),
        ];
    }
}
