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
                'order' => 1,
            ]
        );
    }
}
