<?php

namespace Database\Seeders;

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
            ['node_id' => 3, 'title' => 'Prioritise for personal productivity', 'text' => 'I prioritise tasks effectively and make sure my team and I have the resources we need to deliver efficiently and on time.', 'slug' => 'personal-productivity', 'response_type' => Question::TYPE_SCALE, 'scale_id' => 1, 'required' => true, 'order' => 1, 'active' => true],
            ['node_id' => 3, 'title' => 'Develop personal safety and wellbeing strategies', 'text' => 'I manage my health and wellbeing. I set realistic goals and seek support when I need it.', 'slug' => 'wellbeing-strategies', 'response_type' => Question::TYPE_SCALE, 'scale_id' => 1, 'required' => true, 'order' => 2, 'active' => true],
            ['node_id' => 3, 'title' => 'Commit to continuing professional development', 'text' => 'I continuously build skills and knowledge. I encourage participation in peer learning and development, sharing good practice and seeking feedback.', 'slug' => 'professional-development', 'response_type' => Question::TYPE_SCALE, 'scale_id' => 1, 'required' => true, 'order' => 3, 'active' => true],
        ];
        foreach ($questions as $question) {
            Question::firstOrCreate(
                [
                    'node_id' => $question['node_id'],
                    'title' => $question['title'],
                    'text' => $question['text'],
                    'slug' => $question['slug'],
                    'response_type' => $question['response_type'],
                    'scale_id' => $question['scale_id'],
                    'required' => $question['required'],
                    'order' => $question['order'],
                    'active' => $question['active'],
                ],
            );
        }
    }
}
