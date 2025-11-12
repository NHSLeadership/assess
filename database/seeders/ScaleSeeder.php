<?php

namespace Database\Seeders;

use App\Models\Scale;
use Illuminate\Database\Seeder;

class ScaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $scale = Scale::firstOrCreate(
            ['name' => 'Behaviour likert 1-4'],
            ['description' => 'Insufficient -> Exemplary', 'min_value' => 1, 'max_value' => 4]
        );

        $options = [
            ['label' => 'Insufficient', 'value' => 1, 'order' => 1],
            ['label' => 'Essential', 'value' => 2, 'order' => 2],
            ['label' => 'Proficient', 'value' => 3, 'order' => 3],
            ['label' => 'Exemplary', 'value' => 4, 'order' => 4],
        ];

        foreach ($options as $option) {
            $scale->options()->firstOrCreate(
                ['value' => $option['value']],
                $option
            );
        }

    }
}
