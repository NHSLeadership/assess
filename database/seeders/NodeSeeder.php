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
                'name' => 'Personal impact',
                'description' => 'Personal impact',
                'colour' => 'green',
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
                        'name' => 'Prioritise for personal productivity',
                        'description' => 'Prioritise for personal productivity',
                        'colour' => 'green',
                        'order' => 3,
                    ],
                    [
                        'framework_id' => 1,
                        'parent_id' => 2,
                        'node_type_id' => 3,  // Competency
                        'name' => 'Develop personal health and wellbeing strategies',
                        'description' => 'Develop personal health and wellbeing strategies',
                        'colour' => 'green',
                        'order' => 4,
                    ],
                    [
                        'framework_id' => 1,
                        'parent_id' => 2,
                        'node_type_id' => 3,  // Competency
                        'name' => 'Commit to continuing professional development',
                        'description' => 'Commit to continuing professional development',
                        'colour' => 'green',
                        'order' => 5,
                    ],
                [
                    'framework_id' => 1,
                    'parent_id' => 1,
                    'node_type_id' => 2,  // Standard
                    'name' => 'Communicating Well',
                    'description' => 'Communicating Well',
                    'colour' => 'green',
                    'order' => 6,
                ],
                    [
                        'framework_id' => 1,
                        'parent_id' => 6,
                        'node_type_id' => 3,  // Competency
                        'name' => 'Communicate with clarity and purpose',
                        'description' => 'Communicate with clarity and purpose',
                        'colour' => 'green',
                        'order' => 7,
                    ],
                    [
                        'framework_id' => 1,
                        'parent_id' => 6,
                        'node_type_id' => 3,  // Competency
                        'name' => 'Encourage open dialogue and feedback',
                        'description' => 'Encourage open dialogue and feedback',
                        'colour' => 'green',
                        'order' => 8,
                    ],
                    [
                        'framework_id' => 1,
                        'parent_id' => 6,
                        'node_type_id' => 3,  // Competency
                        'name' => 'Influence, negotiate and manage upwards',
                        'description' => 'Influence, negotiate and manage upwards',
                        'colour' => 'green',
                        'order' => 9,
                    ],
                [
                    'framework_id' => 1,
                    'parent_id' => 1,
                    'node_type_id' => 2,  // Standard
                    'name' => 'Responsibility and integrity',
                    'description' => 'Responsibility and integrity',
                    'colour' => 'green',
                    'order' => 10,
                ],
                    [
                        'framework_id' => 1,
                        'parent_id' => 10,
                        'node_type_id' => 3,  // Competency
                        'name' => 'Take accountability for my actions',
                        'description' => 'Take accountability for my actions',
                        'colour' => 'green',
                        'order' => 11,
                    ],
                    [
                        'framework_id' => 1,
                        'parent_id' => 10,
                        'node_type_id' => 3,  // Competency
                        'name' => 'Be visible, transparent, and present',
                        'description' => 'Be visible, transparent, and present',
                        'colour' => 'green',
                        'order' => 12,
                    ],
                    [
                        'framework_id' => 1,
                        'parent_id' => 10,
                        'node_type_id' => 3,  // Competency
                        'name' => 'Manage with civility and compassion',
                        'description' => 'Manage with civility and compassion',
                        'colour' => 'green',
                        'order' => 13,
                    ],
            [
                'framework_id' => 1,
                'parent_id' => null,
                'node_type_id' => 1,  // Area
                'name' => 'Managing people and resources',
                'description' => 'Managing people and resources',
                'colour' => 'red',
                'order' => 14,
            ],
                [
                    'framework_id' => 1,
                    'parent_id' => 14,
                    'node_type_id' => 2,  // Standard
                    'name' => 'Building teams',
                    'description' => 'Building teams',
                    'colour' => 'red',
                    'order' => 15,
                ],
                    [
                        'framework_id' => 1,
                        'parent_id' => 15,
                        'node_type_id' => 3,  // Competency
                        'name' => 'Build engagement',
                        'description' => 'Build engagement',
                        'colour' => 'red',
                        'order' => 16,
                    ],
                    [
                        'framework_id' => 1,
                        'parent_id' => 15,
                        'node_type_id' => 3,  // Competency
                        'name' => 'Make sure people feel safe in the workplace',
                        'description' => 'Make sure people feel safe in the workplace',
                        'colour' => 'red',
                        'order' => 17,
                    ],
                    [
                        'framework_id' => 1,
                        'parent_id' => 15,
                        'node_type_id' => 3,  // Competency
                        'name' => 'Manage challenges',
                        'description' => 'Manage challenges',
                        'colour' => 'red',
                        'order' => 18,
                    ],
                [
                    'framework_id' => 1,
                    'parent_id' => 14,
                    'node_type_id' => 2,  // Standard
                    'name' => 'Performance and delivery',
                    'description' => 'Performance and delivery',
                    'colour' => 'red',
                    'order' => 19,
                ],
                    [
                        'framework_id' => 1,
                        'parent_id' => 19,
                        'node_type_id' => 3,  // Competency
                        'name' => 'Provide clear purpose, vision, and deliverables',
                        'description' => 'Provide clear purpose, vision, and deliverables',
                        'colour' => 'red',
                        'order' => 20,
                    ],
                    [
                        'framework_id' => 1,
                        'parent_id' => 19,
                        'node_type_id' => 3,  // Competency
                        'name' => 'Manage and measure performance',
                        'description' => 'Manage and measure performance',
                        'colour' => 'red',
                        'order' => 21,
                    ],
                    [
                        'framework_id' => 1,
                        'parent_id' => 19,
                        'node_type_id' => 3,  // Competency
                        'name' => 'Manage conflict and sensitive conversations',
                        'description' => 'Manage conflict and sensitive conversations',
                        'colour' => 'red',
                        'order' => 22,
                    ],
                [
                    'framework_id' => 1,
                    'parent_id' => 14,
                    'node_type_id' => 2,  // Standard
                    'name' => 'Efficiency and effectiveness',
                    'description' => 'Efficiency and effectiveness',
                    'colour' => 'red',
                    'order' => 23,
                ],
                    [
                        'framework_id' => 1,
                        'parent_id' => 23,
                        'node_type_id' => 3,  // Competency
                        'name' => 'Allocate and optimise the use of resources',
                        'description' => 'Allocate and optimise the use of resources',
                        'colour' => 'red',
                        'order' => 24,
                    ],
                    [
                        'framework_id' => 1,
                        'parent_id' => 23,
                        'node_type_id' => 3,  // Competency
                        'name' => 'Maximise outputs and get best value for public money',
                        'description' => 'Maximise outputs and get best value for public money',
                        'colour' => 'red',
                        'order' => 25,
                    ],
                    [
                        'framework_id' => 1,
                        'parent_id' => 23,
                        'node_type_id' => 3,  // Competency
                        'name' => 'Use data, evidence and critical thinking',
                        'description' => 'Use data, evidence and critical thinking',
                        'colour' => 'red',
                        'order' => 26,
                    ],
            [
                'framework_id' => 1,
                'parent_id' => null,
                'node_type_id' => 1,  // Area
                'name' => 'Delivering across health and care',
                'description' => 'Delivering across health and care',
                'colour' => 'purple',
                'order' => 27,
            ],
                [
                    'framework_id' => 1,
                    'parent_id' => 27,
                    'node_type_id' => 2,  // Standard
                    'name' => 'Improving quality',
                    'description' => 'Improving quality',
                    'colour' => 'purple',
                    'order' => 28,
                ],
                    [
                        'framework_id' => 1,
                        'parent_id' => 28,
                        'node_type_id' => 3,  // Competency
                        'name' => 'Respond to patient safety concerns, needs and preferences',
                        'description' => 'Respond to patient safety concerns, needs and preferences',
                        'colour' => 'purple',
                        'order' => 29,
                    ],
                    [
                        'framework_id' => 1,
                        'parent_id' => 28,
                        'node_type_id' => 3,  // Competency
                        'name' => 'Personalise care',
                        'description' => 'Personalise care',
                        'colour' => 'purple',
                        'order' => 30,
                    ],
                    [
                        'framework_id' => 1,
                        'parent_id' => 28,
                        'node_type_id' => 3,  // Competency
                        'name' => 'Implement policies and ensure good governance',
                        'description' => 'Implement policies and ensure good governance',
                        'colour' => 'purple',
                        'order' => 31,
                    ],
                [
                    'framework_id' => 1,
                    'parent_id' => 27,
                    'node_type_id' => 2,  // Standard
                    'name' => 'Innovation and improvement',
                    'description' => 'Innovation and improvement',
                    'colour' => 'purple',
                    'order' => 32,
                ],
                    [
                        'framework_id' => 1,
                        'parent_id' => 32,
                        'node_type_id' => 3,  // Competency
                        'name' => 'Drive continuous improvement and innovation',
                        'description' => 'Drive continuous improvement and innovation',
                        'colour' => 'purple',
                        'order' => 33,
                    ],
                    [
                        'framework_id' => 1,
                        'parent_id' => 32,
                        'node_type_id' => 3,  // Competency
                        'name' => 'Transform through technology and innovation',
                        'description' => 'Transform through technology and innovation',
                        'colour' => 'purple',
                        'order' => 34,
                    ],
                    [
                        'framework_id' => 1,
                        'parent_id' => 32,
                        'node_type_id' => 3,  // Competency
                        'name' => 'Support others through change',
                        'description' => 'Support others through change',
                        'colour' => 'purple',
                        'order' => 35,
                    ],
            [
                    'framework_id' => 1,
                    'parent_id' => 27,
                    'node_type_id' => 2,  // Standard
                    'name' => 'Working collaboratively',
                    'description' => 'Working collaboratively',
                    'colour' => 'purple',
                    'order' => 36,
                ],
                    [
                        'framework_id' => 1,
                        'parent_id' => 36,
                        'node_type_id' => 3,  // Competency
                        'name' => 'Build relationships',
                        'description' => 'Build relationships',
                        'colour' => 'purple',
                        'order' => 37,
                    ],
                    [
                        'framework_id' => 1,
                        'parent_id' => 36,
                        'node_type_id' => 3,  // Competency
                        'name' => 'Lead a collaborative team',
                        'description' => 'Lead a collaborative team',
                        'colour' => 'purple',
                        'order' => 38,
                    ],
                    [
                        'framework_id' => 1,
                        'parent_id' => 36,
                        'node_type_id' => 3,  // Competency
                        'name' => 'Share good practice',
                        'description' => 'Share good practice',
                        'colour' => 'purple',
                        'order' => 39,
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
