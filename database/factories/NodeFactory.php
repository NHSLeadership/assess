<?php

namespace Database\Factories;

use App\Enums\NodeColour;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Node>
 */
class NodeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->unique()->numberBetween(1000, 999999), // if your PK is UUID
            'node_type_id' => $this->faker->unique()->numberBetween(1000, 999999),
            'colour' => $this->faker->randomElement(
                array_map(fn ($case) => $case->value, NodeColour::cases())
            ),
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
        ];
    }
}
