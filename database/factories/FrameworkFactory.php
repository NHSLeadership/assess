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
            'id'          => $this->faker->unique()->numberBetween(1000, 999999), // if your PK is UUID
            'name'        => $this->faker->text(100),   // returns "Leadership Framework"
            'slug'        => $this->faker->slug(),
            'description' => $this->faker->paragraph(),
            'instructions'=> $this->faker->text(100),
        ];

    }
}
