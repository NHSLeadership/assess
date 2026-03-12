<?php

namespace Database\Factories;

use App\Models\Framework;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Framework>
 */
class FrameworkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->text(100),   // returns "Leadership Framework"
            'slug' => $this->faker->slug(),
            'description' => $this->faker->paragraph(),
            'instructions' => $this->faker->text(100),
            'report_intro' => $this->faker->paragraph(),
            'report_html' => $this->faker->randomHtml(),
            'report_ending' => $this->faker->paragraph(),
        ];

    }
}
