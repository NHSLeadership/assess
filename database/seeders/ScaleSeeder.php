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
            ['name' => 'Agreement likert 1-4'],
            ['description' => 'Strongly disagree -> Strongly agree']
        );

        $options = [
            ['label' => 'Strongly disagree', 'value' => 1, 'order' => 1],
            ['label' => 'Mostly disagree', 'value' => 2, 'order' => 3],
            ['label' => 'Mostly agree', 'value' => 3, 'order' => 2],
            ['label' => 'Strongly agree', 'value' => 4, 'order' => 4],
            ['label' => 'Not applicable for my role', 'value' => 0, 'order' => 5],
        ];

        foreach ($options as $option) {
            $scale->options()->firstOrCreate(
                ['value' => $option['value']],
                $option
            );
        }

    }
}
