<?php

namespace Database\Seeders;

use App\Models\Node;
use Illuminate\Database\Seeder;

class NodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $nodes = [
            [
                'framework_id' => 1,
                'parent_id' => null,
                'node_type_id' => 1,  // Area
                'name' => 'Developing self',
                'description' => 'Developing self',
                'order' => 1,
            ],
                [
                    'framework_id' => 1,
                    'parent_id' => 1,
                    'node_type_id' => 2,  // Standard
                    'name' => 'Personal productivity and wellbeing',
                    'description' => 'Personal productivity and wellbeing',
                    'colour' => 'green',
                    'order' => 2,
                ],
                    [
                        'framework_id' => 1,
                        'parent_id' => 2,
                        'node_type_id' => 3,  // Competency
                        'name' => 'Prioritise for productivity',
                        'description' => 'Prioritise for productivity',
                        'order' => 3,
                    ],
                    [
                        'framework_id' => 1,
                        'parent_id' => 2,
                        'node_type_id' => 3,  // Competency
                        'name' => 'Keep safe and develop wellbeing strategies',
                        'description' => 'Keep safe and develop wellbeing strategies',
                        'order' => 4,
                    ],
                [
                    'framework_id' => 1,
                    'parent_id' => 1,
                    'node_type_id' => 2,  // Standard
                    'name' => 'Communicating Well',
                    'description' => 'Communicating Well',
                    'order' => 5,
                ],
                [
                    'framework_id' => 1,
                    'parent_id' => 1,
                    'node_type_id' => 2,  // Standard
                    'name' => 'Responsibility and integrity',
                    'description' => 'Responsibility and integrity',
                    'order' => 6,
                ],
            [
                'framework_id' => 1,
                'parent_id' => null,
                'node_type_id' => 1,  // Area
                'name' => 'Managing people and resources',
                'description' => 'Managing people and resources',
                'colour' => 'green',
                'order' => 7,
            ],
                [
                    'framework_id' => 1,
                    'parent_id' => 7,
                    'node_type_id' => 2,  // Standard
                    'colour' => 'orange',
                    'name' => 'Building teams',
                    'description' => 'Building teams',
                    'order' => 8,
                ],
                [
                    'framework_id' => 1,
                    'parent_id' => 7,
                    'node_type_id' => 2,  // Standard
                    'name' => 'Performance and delivery',
                    'description' => 'Performance and delivery',
                    'order' => 9,
                ],
                [
                    'framework_id' => 1,
                    'parent_id' => 7,
                    'node_type_id' => 2,  // Standard
                    'name' => 'Efficiency and effectiveness',
                    'description' => 'Efficiency and effectiveness',
                    'order' => 10,
                ],
            [
                'framework_id' => 1,
                'parent_id' => null,
                'node_type_id' => 1,  // Area
                'name' => 'Delivering across health and care',
                'description' => 'Delivering across health and care',
                'colour' => 'orange',
                'order' => 11,
            ],
                [
                    'framework_id' => 1,
                    'parent_id' => 11,
                    'node_type_id' => 2,  // Standard
                    'name' => 'Improving quality',
                    'description' => 'Improving quality',
                    'order' => 12,
                ],
                [
                    'framework_id' => 1,
                    'parent_id' => 11,
                    'node_type_id' => 2,  // Standard
                    'name' => 'Innovation and improvement',
                    'description' => 'Innovation and improvement',
                    'order' => 13,
                ],
                [
                    'framework_id' => 1,
                    'parent_id' => 11,
                    'node_type_id' => 2,  // Standard
                    'name' => 'Working collaboratively',
                    'description' => 'Working collaboratively',
                    'order' => 14,
                ],
        ];
        foreach ($nodes as $node) {
            Node::firstOrCreate(
                [
                    'framework_id' => $node['framework_id'],
                    'parent_id' => $node['parent_id'],
                    'node_type_id' => $node['node_type_id'],
                    'name' => $node['name'],
                    'description' => $node['description'],
                    'colour' => $node['colour'] ?? 'blue',
                    'order' => $node['order'],
                ],
            );
        }
    }
}
