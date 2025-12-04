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
        ]);
    }
}
