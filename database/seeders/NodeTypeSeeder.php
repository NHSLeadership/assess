<?php

namespace Database\Seeders;

use App\Models\NodeType;
use Illuminate\Database\Seeder;

class NodeTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['name' => 'Area', 'slug' => 'area'],
            ['name' => 'Standard', 'slug' => 'standard'],
            ['name' => 'Competency', 'slug' => 'competency'],
        ];
        foreach ($types as $type) {
            NodeType::firstOrCreate(
                ['name' => $type['name']],
                ['slug' => $type['slug']],
            );
        }
    }
}
