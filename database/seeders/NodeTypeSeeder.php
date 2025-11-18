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
            ['name' => 'Area'],
            ['name' => 'Standard'],
            ['name' => 'Competency'],
        ];
        foreach ($types as $type) {
            NodeType::firstOrCreate(
                ['name' => $type['name']],
            );
        }
    }
}
