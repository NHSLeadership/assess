<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'id' => $this->faker->unique()->numberBetween(1000, 999999), // if your PK is UUID
            'email' => $this->faker->unique()->safeEmail(),
            'name' => $this->faker->name(),
        ];
    }
}
