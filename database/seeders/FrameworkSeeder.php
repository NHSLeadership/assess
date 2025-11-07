<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FrameworkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Framework::factory()->createManyQuietly([
            ['stage_id' => 2, 'slug' => 'self-framework-s1', 'name' => 'Self assessment S1', 'description' => ''],
            ['stage_id' => 2, 'slug' => '360-framework-s1', 'name' => '360 framework S1', 'description' => ''],
            ['stage_id' => 3, 'slug' => 'self-framework-s2', 'name' => 'Self assessment S2', 'description' => ''],
            ['stage_id' => 3, 'slug' => '360-framework-s2', 'name' => '360 framework S2', 'description' => ''],
        ]);
    }
}
