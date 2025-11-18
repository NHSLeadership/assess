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
        Framework::firstOrCreate(
            [
                'name'        => 'Management and Leadership framework',
                'slug'        => 'management-and-leadership-framework',
                'description' => 'NHS Management and Leadership framework.',
            ]
        );
    }
}
