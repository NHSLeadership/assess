<?php

namespace Database\Factories;

use App\Models\Framework;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Framework>
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
            'name'        => $this->faker->text(100),   // returns "Leadership Framework"
            'slug'        => $this->faker->slug(),
            'description' => $this->faker->paragraph(),
            'instructions'=> $this->faker->text(100),
            'report_intro'=> $this->faker->paragraph(),
            'report_ending'=> $this->faker->paragraph(),
        ];

    }
}
