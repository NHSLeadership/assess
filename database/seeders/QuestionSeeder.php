<?php

namespace Database\Seeders;

use App\Enums\ResponseType;
use App\Models\Question;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $questions = [
            // Developing self → Personal productivity and wellbeing (node_ids: 3,4,5)
            ['node_id' => 3],
            ['node_id' => 4],
            ['node_id' => 5],

            // Developing self → Communicating well (node_ids: 7,8,9)
            ['node_id' => 7],
            ['node_id' => 8],
            ['node_id' => 9],

            // Developing self → Responsibility and integrity (node_ids: 11,12,13)
            ['node_id' => 11],
            ['node_id' => 12],
            ['node_id' => 13],

            // Managing people and resources → Building teams (node_ids: 16,17,18)
            ['node_id' => 16],
            ['node_id' => 17],
            ['node_id' => 18],

            // Managing people and resources → Performance and delivery (node_ids: 20,21,22)
            ['node_id' => 20],
            ['node_id' => 21],
            ['node_id' => 22],

            // Managing people and resources → Efficiency and effectiveness (node_ids: 24,25,26)
            ['node_id' => 24],
            ['node_id' => 25],
            ['node_id' => 26],

            // Delivering across health and care → Improving quality (node_ids: 29,30,31)
            ['node_id' => 29],
            ['node_id' => 30],
            ['node_id' => 31],

            // Delivering across health and care → Innovation and improvement (node_ids: 33,34,35)
            ['node_id' => 33],
            ['node_id' => 34],
            ['node_id' => 35],

            // Delivering across health and care → Working collaboratively (node_ids: 37,38,39)
            ['node_id' => 37],
            ['node_id' => 38],
            ['node_id' => 39],
        ];

        foreach ($questions as $question) {
            // Scale questions
            Question::firstOrCreate([
                'node_id'       => $question['node_id'],
                'title'         => 'Scale',
                'text'          => '<p><strong>Consider the extent to which you demonstrate each competence in your work, using the following capability scale</strong></p>',
                'response_type' => ResponseType::TYPE_SCALE,
                'scale_id'      => 1,
                'required'      => true,
                'order'         => 1,
                'active'        => true,
            ]);
            // Text questions
            Question::firstOrCreate([
                'node_id'       => $question['node_id'],
                'title'         => 'Reflections',
                'text'          => '<p><strong>Optional free text</strong></p>',
                'response_type' => ResponseType::TYPE_TEXTAREA,
                'required'      => false,
                'order'         => 2,
                'active'        => true,
            ]);
        }
    }
}
