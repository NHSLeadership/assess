<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Area::factory()->createManyQuietly([
            ['name' => 'Self effectiveness', 'colour' => 'green', 'description' => ''],
            ['name' => 'Managing People and Resources', 'colour' => 'orange', 'description' => ''],
            ['name' => 'Delivering Across Health and Social Care', 'colour' => 'blue', 'description' => ''],
        ]);
    }
}
