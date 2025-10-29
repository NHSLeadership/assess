<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssessmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Assessment::factory()->createManyQuietly([
            ['stage_id' => 1, 'slug' => 'self-assessment-f', 'name' => 'Self assessment (Fundamentals)', 'description' => 'Self assessment (Fundamentals)'],
            ['stage_id' => 2, 'slug' => 'self-assessment-s1', 'name' => 'Self assessment (Stage 1)', 'description' => 'Self assessment (Stage 1)'],
            ['stage_id' => 2, 'slug' => '360-assessment-s1', 'name' => '360 assessment (Stage 1)', 'description' => '360 assessment (Stage 1)'],
        ]);
    }
}
