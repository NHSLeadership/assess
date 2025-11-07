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
            ['framework_id' => 1, 'parent_id' => null, 'name' => 'Developing self', 'colour' => 'green', 'description' => ''],
            ['framework_id' => 1, 'parent_id' => null, 'name' => 'Managing people and resources', 'colour' => 'orange', 'description' => ''],
            ['framework_id' => 1, 'parent_id' => null, 'name' => 'Delivering across health and care', 'colour' => 'blue', 'description' => ''],

            ['framework_id' => 1, 'parent_id' => 1, 'name' => 'Balancing safety, productivity and wellbeing', 'slug' => 'balancing-safety-productivity-and-wellbeing', 'description' => ''],
            ['framework_id' => 1, 'parent_id' => 1, 'name' => 'Communicating and listening effectively', 'slug' => 'communicating-and-listening-effectively', 'description' => ''],
            ['framework_id' => 1, 'parent_id' => 1, 'name' => 'Delivering with integrity and accountability', 'slug' => 'delivering-with-integrity-and-accountability', 'description' => ''],
            ['framework_id' => 1, 'parent_id' => 2, 'name' => 'Creating a high-performing work environment', 'slug' => 'creating-a-high-performing-work-environment', 'description' => ''],
            ['framework_id' => 1, 'parent_id' => 2, 'name' => 'Managing performance', 'slug' => 'managing-performance', 'description' => ''],
            ['framework_id' => 1, 'parent_id' => 2, 'name' => 'Maximising resources and efficiencies', 'slug' => 'maximising-resources-and-efficiencies', 'description' => ''],
            ['framework_id' => 1, 'parent_id' => 3, 'name' => 'Improving patient outcomes and experiences', 'slug' => 'improving-patient-outcomes-and-experiences', 'description' => ''],
            ['framework_id' => 1, 'parent_id' => 3, 'name' => 'Leading with a mindset of improvement and innovation', 'slug' => 'leading-with-a-mindset-of-improvement-and-innovation', 'description' => ''],
            ['framework_id' => 1, 'parent_id' => 3, 'name' => 'Working collaboratively to achieve results', 'slug' => 'working-collaboratively-to-achieve-results', 'description' => ''],
        ]);
    }
}
