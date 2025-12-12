<?php

namespace Database\Seeders;

use App\Models\Framework;
use Illuminate\Database\Seeder;

class FrameworkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Framework::firstOrCreate([
            'name'        => 'Management and Leadership framework',
            'slug'        => 'mlf',
            'description' => 'The framework consists of a code of practice, standards and competencies at all levels from entry level manager to executive, and a learning and development curriculum.',
            'instructions' => '<h2>Welcome to your NHS Management and Leadership assessment</h2><p>In this assessment you will be presented with a series of questions.</p><p>To get the most value from this exercise answer these questions as accurately and honestly as you can.</p>',
        ]);
    }
}
