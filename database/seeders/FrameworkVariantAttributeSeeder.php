<?php

namespace Database\Seeders;

use App\Models\FrameworkVariantAttribute;
use Illuminate\Database\Seeder;

class FrameworkVariantAttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FrameworkVariantAttribute::firstOrCreate(
            [
                'framework_id' => 1,
                'key' => 'stage',
                'label' => 'Career stage',
                'hint_text' => '<p>This framework is contextual to your current role. Please select the stage you operate at. If you need assistance selecting this, please visit <a href="https://mlframework.leadershipacademy.nhs.uk">mlframework.leadershipacademy.nhs.uk</a> or speak with your line manager.</p>',
                'order' => 1,
            ]
        );
    }
}
