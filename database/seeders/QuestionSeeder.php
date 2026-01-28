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
            ['node_id' => 3,  'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Prioritise for personal productivity', 'text' => '<p><strong>Consider the extent to which you demonstrate each competence in your work, using the following capability scale:</strong></p>', 'required' => true, 'order' => 0, 'active' => true],
            ['node_id' => 4,  'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Develop personal health and wellbeing strategies', 'text' => '<p><strong>Consider the extent to which you demonstrate each competence in your work, using the following capability scale:</strong></p>', 'required' => true, 'order' => 0, 'active' => true],
            ['node_id' => 5,  'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Commit to continuing professional development', 'text' => '<p><strong>Consider the extent to which you demonstrate each competence in your work, using the following capability scale:</strong></p>', 'required' => true, 'order' => 0, 'active' => true],

            // Developing self → Communicating well (node_ids: 7,8,9)
            ['node_id' => 7,  'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Communicate with clarity and purpose', 'text' => '<p><strong>Consider the extent to which you demonstrate each competence in your work, using the following capability scale:</strong></p>', 'required' => true, 'order' => 0, 'active' => true],
            ['node_id' => 8,  'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Encourage open dialogue and feedback', 'text' => '<p><strong>Consider the extent to which you demonstrate each competence in your work, using the following capability scale:</strong></p>', 'required' => true, 'order' => 0, 'active' => true],
            ['node_id' => 9,  'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Influence, negotiate and manage upwards', 'text' => '<p><strong>Consider the extent to which you demonstrate each competence in your work, using the following capability scale:</strong></p>', 'required' => true, 'order' => 0, 'active' => true],

            // Developing self → Responsibility and integrity (node_ids: 11,12,13)
            ['node_id' => 11, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Take accountability for my actions', 'text' => '<p><strong>Consider the extent to which you demonstrate each competence in your work, using the following capability scale:</strong></p>', 'required' => true, 'order' => 0, 'active' => true],
            ['node_id' => 12, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Be visible, transparent, and present', 'text' => '<p><strong>Consider the extent to which you demonstrate each competence in your work, using the following capability scale:</strong></p>', 'required' => true, 'order' => 0, 'active' => true],
            ['node_id' => 13, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Manage with civility and compassion', 'text' => '<p><strong>Consider the extent to which you demonstrate each competence in your work, using the following capability scale:</strong></p>', 'required' => true, 'order' => 0, 'active' => true],

            // Managing people and resources → Building teams (node_ids: 16,17,18)
            ['node_id' => 16, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Build engagement', 'text' => '<p><strong>Consider the extent to which you demonstrate each competence in your work, using the following capability scale:</strong></p>', 'required' => true, 'order' => 0, 'active' => true],
            ['node_id' => 17, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Make sure people feel safe in the workplace', 'text' => '<p><strong>Consider the extent to which you demonstrate each competence in your work, using the following capability scale:</strong></p>', 'required' => true, 'order' => 0, 'active' => true],
            ['node_id' => 18, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Manage challenges', 'text' => '<p><strong>Consider the extent to which you demonstrate each competence in your work, using the following capability scale:</strong></p>', 'required' => true, 'order' => 0, 'active' => true],

            // Managing people and resources → Performance and delivery (node_ids: 20,21,22)
            ['node_id' => 20, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Provide clear purpose, vision, and deliverables', 'text' => '<p><strong>Consider the extent to which you demonstrate each competence in your work, using the following capability scale:</strong></p>', 'required' => true, 'order' => 0, 'active' => true],
            ['node_id' => 21, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Manage and measure performance', 'text' => '<p><strong>Consider the extent to which you demonstrate each competence in your work, using the following capability scale:</strong></p>', 'required' => true, 'order' => 0, 'active' => true],
            ['node_id' => 22, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Manage conflict and sensitive conversations', 'text' => '<p><strong>Consider the extent to which you demonstrate each competence in your work, using the following capability scale:</strong></p>', 'required' => true, 'order' => 0, 'active' => true],

            // Managing people and resources → Efficiency and effectiveness (node_ids: 24,25,26)
            ['node_id' => 24, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Allocate and optimise the use of resources', 'text' => '<p><strong>Consider the extent to which you demonstrate each competence in your work, using the following capability scale:</strong></p>', 'required' => true, 'order' => 0, 'active' => true],
            ['node_id' => 25, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Maximise outputs and get best value for public money', 'text' => '<p><strong>Consider the extent to which you demonstrate each competence in your work, using the following capability scale:</strong></p>', 'required' => true, 'order' => 0, 'active' => true],
            ['node_id' => 26, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Use data, evidence and critical thinking', 'text' => '<p><strong>Consider the extent to which you demonstrate each competence in your work, using the following capability scale:</strong></p>', 'required' => true, 'order' => 0, 'active' => true],

            // Delivering across health and care → Improving quality (node_ids: 29,30,31)
            ['node_id' => 29, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Respond to patient safety concerns, needs and preferences', 'text' => '<p><strong>Consider the extent to which you demonstrate each competence in your work, using the following capability scale:</strong></p>', 'required' => true, 'order' => 0, 'active' => true],
            ['node_id' => 30, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Personalise care', 'text' => '<p><strong>Consider the extent to which you demonstrate each competence in your work, using the following capability scale:</strong></p>', 'required' => true, 'order' => 0, 'active' => true],
            ['node_id' => 31, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Implement policies and ensure good governance', 'text' => '<p><strong>Consider the extent to which you demonstrate each competence in your work, using the following capability scale:</strong></p>', 'required' => true, 'order' => 0, 'active' => true],

            // Delivering across health and care → Innovation and improvement (node_ids: 33,34,35)
            ['node_id' => 33, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Drive continuous improvement and innovation', 'text' => '<p><strong>Consider the extent to which you demonstrate each competence in your work, using the following capability scale:</strong></p>', 'required' => true, 'order' => 0, 'active' => true],
            ['node_id' => 34, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Transform through technology and innovation', 'text' => '<p><strong>Consider the extent to which you demonstrate each competence in your work, using the following capability scale:</strong></p>', 'required' => true, 'order' => 0, 'active' => true],
            ['node_id' => 35, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Support others through change', 'text' => '<p><strong>Consider the extent to which you demonstrate each competence in your work, using the following capability scale:</strong></p>', 'required' => true, 'order' => 0, 'active' => true],

            // Delivering across health and care → Working collaboratively (node_ids: 37,38,39) — corrected
            ['node_id' => 37, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Build relationships', 'text' => '<p><strong>Consider the extent to which you demonstrate each competence in your work, using the following capability scale:</strong></p>', 'required' => true, 'order' => 0, 'active' => true],
            ['node_id' => 38, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Lead a collaborative team', 'text' => '<p><strong>Consider the extent to which you demonstrate each competence in your work, using the following capability scale:</strong></p>', 'required' => true, 'order' => 0, 'active' => true],
            ['node_id' => 39, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Share good practice', 'text' => '<p><strong>Consider the extent to which you demonstrate each competence in your work, using the following capability scale:</strong></p>', 'required' => true, 'order' => 0, 'active' => true],
        ];

        foreach ($questions as $question) {
            Question::firstOrCreate([
                'node_id'       => $question['node_id'],
                'title'         => $question['title'],
                'text'          => $question['text'],
                'response_type' => $question['response_type'],
                'scale_id'      => $question['scale_id'],
                'required'      => $question['required'],
                'order'         => $question['order'],
                'active'        => $question['active'],
            ]);
        }
    }
}
