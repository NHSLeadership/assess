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
            ['slug' => 'hlm'],
            [
                'name'        => 'Management and Leadership framework',
                'slug'        => 'management-and-leadership-framework',
                'description' => 'The NHS Management and Leadership framework.',
                'active'      => true,
            ]
        );
    }
}
