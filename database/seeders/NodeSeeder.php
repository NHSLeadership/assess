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
                'slug' => 'developing-self',
                'description' => 'Developing self',
                'order' => 1,
            ],
                [
                    'framework_id' => 1,
                    'parent_id' => 1,
                    'node_type_id' => 2,  // Standard
                    'name' => 'Personal productivity and wellbeing',
                    'slug' => 'personal-productivity-and-wellbeing',
                    'description' => 'Personal productivity and wellbeing',
                    'order' => 2,
                ],
                    [
                        'framework_id' => 1,
                        'parent_id' => 2,
                        'node_type_id' => 3,  // Competency
                        'name' => 'Prioritise for productivity',
                        'slug' => 'prioritise-for-productivity',
                        'description' => 'Prioritise for productivity',
                        'order' => 3,
                    ],
                    [
                        'framework_id' => 1,
                        'parent_id' => 2,
                        'node_type_id' => 3,  // Competency
                        'name' => 'Keep safe and develop wellbeing strategies',
                        'slug' => 'keep-safe-and-develop-wellbeing-strategies',
                        'description' => 'Keep safe and develop wellbeing strategies',
                        'order' => 4,
                    ],
                [
                    'framework_id' => 1,
                    'parent_id' => 1,
                    'node_type_id' => 2,  // Standard
                    'name' => 'Communicating Well',
                    'slug' => 'communicating-well',
                    'description' => 'Communicating Well',
                    'order' => 5,
                ],
                [
                    'framework_id' => 1,
                    'parent_id' => 1,
                    'node_type_id' => 2,  // Standard
                    'name' => 'Responsibility and integrity',
                    'slug' => 'responsibility-and-integrity',
                    'description' => 'Responsibility and integrity',
                    'order' => 6,
                ],
            [
                'framework_id' => 1,
                'parent_id' => null,
                'node_type_id' => 1,  // Area
                'name' => 'Managing people and resources',
                'slug' => 'managing-people-and-resources',
                'description' => 'Managing people and resources',
                'order' => 7,
            ],
                [
                    'framework_id' => 1,
                    'parent_id' => 7,
                    'node_type_id' => 2,  // Standard
                    'name' => 'Building teams',
                    'slug' => 'building-teams',
                    'description' => 'Building teams',
                    'order' => 8,
                ],
                [
                    'framework_id' => 1,
                    'parent_id' => 7,
                    'node_type_id' => 2,  // Standard
                    'name' => 'Performance and delivery',
                    'slug' => 'performance-and-delivery',
                    'description' => 'Performance and delivery',
                    'order' => 9,
                ],
                [
                    'framework_id' => 1,
                    'parent_id' => 7,
                    'node_type_id' => 2,  // Standard
                    'name' => 'Efficiency and effectiveness',
                    'slug' => 'efficiency-and-effectiveness',
                    'description' => 'Efficiency and effectiveness',
                    'order' => 10,
                ],
            [
                'framework_id' => 1,
                'parent_id' => null,
                'node_type_id' => 1,  // Area
                'name' => 'Delivering across health and care',
                'slug' => 'delivering-across-health-and-care',
                'description' => 'Delivering across health and care',
                'order' => 11,
            ],
                [
                    'framework_id' => 1,
                    'parent_id' => 11,
                    'node_type_id' => 2,  // Standard
                    'name' => 'Improving quality',
                    'slug' => 'improving-quality',
                    'description' => 'Improving quality',
                    'order' => 12,
                ],
                [
                    'framework_id' => 1,
                    'parent_id' => 11,
                    'node_type_id' => 2,  // Standard
                    'name' => 'Innovation and improvement',
                    'slug' => 'innovation-and-improvement',
                    'description' => 'Innovation and improvement',
                    'order' => 13,
                ],
                [
                    'framework_id' => 1,
                    'parent_id' => 11,
                    'node_type_id' => 2,  // Standard
                    'name' => 'Working collaboratively',
                    'slug' => 'working-collaboratively',
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
                'slug' => $node['slug'],
                'description' => $node['description'],
                'order' => $node['order'],
                ],
            );
        }
    }
}
