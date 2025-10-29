<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class FormFieldFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->sentence(1);

        return [
            'form_id' => \App\Models\Form::factory(),
            'element' => $this->faker->randomElement(['text', 'textarea', 'select', 'radio', 'checkbox']),
            'name' => $name,
            'label' => $name,
            'placeholder' => $name,
            'hint' => $this->faker->optional()->sentence(5),
            'order' => $this->faker->numberBetween(1, 10),
            'defaults' => null,
            'minLength' => null,
            'maxLength' => null,
            'required' => $this->faker->boolean(70),
        ];
    }
}
