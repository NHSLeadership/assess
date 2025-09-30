<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Forms extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Form::factory()->createMany([
            ['name' => 'Competency 1', 'slug' => 'competency-1', 'description' => 'Description for Competency 1'],
            ['name' => 'Competency 2', 'slug' => 'competency-2', 'description' => 'Description for Competency 2'],
            ['name' => 'Competency 3', 'slug' => 'competency-3', 'description' => 'Description for Competency 3'],
        ]);
    }
}
