<?php

namespace Database\Seeders;

use App\Models\Scale;
use Illuminate\Database\Seeder;

class ScaleSeeder extends Seeder
{
    public function run(): void
    {
        $scale = Scale::firstOrCreate(
            ['name' => 'Capability likert 1-5'],
            ['description' => 'Insufficient -> Exemplary']
        );

        $options = [
            [
                'label' => 'Insufficient',
                'description' => 'I can’t do this without help',
                'value' => 1,
                'order' => 1,
            ],
            [
                'label' => 'Essential',
                'description' => 'I can do parts of this but need guidance',
                'value' => 2,
                'order' => 2,
            ],
            [
                'label' => 'Proficient',
                'description' => 'I can do most of this with occasional help',
                'value' => 3,
                'order' => 3,
            ],
            [
                'label' => 'Strong',
                'description' => 'I can do all of this independently',
                'value' => 4,
                'order' => 4,
            ],
            [
                'label' => 'Very strong',
                'description' => 'I do this effectively and can support others to do this',
                'value' => 5,
                'order' => 5,
            ],
        ];

        foreach ($options as $option) {
            $scale->options()->updateOrCreate(
                ['value' => $option['value']],
                $option
            );
        }
    }
}
