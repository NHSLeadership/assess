<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\NodeColour;
use App\Models\Framework;
use App\Models\Node;
use App\Models\NodeType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Node>
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
            'framework_id' => Framework::factory(),
            'id' => $this->faker->unique()->numberBetween(1000, 999999), // if your PK is UUID
            'node_type_id' => NodeType::factory(),
            'colour' => $this->faker->randomElement(
                array_map(fn (\App\Enums\NodeColour $case) => $case->value, NodeColour::cases())
            ),
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
        ];
    }
}
