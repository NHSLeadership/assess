<?php

namespace Database\Seeders;

use App\Models\FrameworkVariantOption;
use Illuminate\Database\Seeder;

class FrameworkVariantOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $options = [
            [
                'framework_variant_attribute_id' => 1,
                'value' => 'stage 1',
                'label' => 'Stage 1: First time managers and leaders',
                'order' => 1,
            ],
            [
                'framework_variant_attribute_id' => 1,
                'value' => 'stage 2',
                'label' => 'Stage 2: Mid-level managers and leaders',
                'order' => 2,
            ],
            [
                'framework_variant_attribute_id' => 1,
                'value' => 'stage 3',
                'label' => 'Stage 3: Senior managers and leaders',
                'order' => 3,
            ],
            [
                'framework_variant_attribute_id' => 1,
                'value' => 'stage 4',
                'label' => 'Stage 4: Executive managers and leaders',
                'order' => 4,
            ],
        ];
        foreach ($options as $option) {
            FrameworkVariantOption::firstOrCreate([
                'framework_variant_attribute_id' => $option['framework_variant_attribute_id'],
                'value' => $option['value'],
                'label' => $option['label'],
                'order' => $option['order']
                ],
            );
        }
    }
}
