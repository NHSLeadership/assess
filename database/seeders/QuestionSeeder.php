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
            ['node_id' => 3, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Prioritise for personal productivity', 'text' => 'Prioritising tasks effectively and making sure the team have the resources we need to deliver efficiently and on time.', 'required' => true, 'order' => 0, 'active' => true],
            ['node_id' => 4, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Develop personal health and wellbeing strategies', 'text' => 'Managing own health and wellbeing, setting realistic goals and seeking support when needed.', 'required' => true, 'order' => 0, 'active' => true],
            ['node_id' => 5, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Commit to continuing professional development', 'text' => 'Continuously building skills and knowledge, encouraging peer learning and development, sharing good practice and seeking feedback.', 'required' => true, 'order' => 0, 'active' => true],

            ['node_id' => 7, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Communicate with clarity and purpose', 'text' => 'Communicating to suit the audience and situation – and asking for feedback to check people have understood. ', 'required' => true, 'order' => 0, 'active' => true],
            ['node_id' => 8, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Encourage open dialogue and feedback', 'text' => 'Helping colleagues and patients to speak up, listening and following up on what they say.', 'required' => true, 'order' => 0, 'active' => true],
            ['node_id' => 9, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Influence, negotiate and manage upwards', 'text' => 'Making sure people’s voices are heard and concerns are escalated, negotiating on their behalf if necessary.', 'required' => true, 'order' => 0, 'active' => true],

//            ['node_id' => 5, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Take accountability for my actions', 'text' => 'I reflect on my actions, reactions and biases, both conscious and unconscious, to better understand and manage their impact on myself and others.', 'required' => true, 'order' => 0, 'active' => true],
//            ['node_id' => 5, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Be visible, transparent and present', 'text' => 'I develop trust and engagement with others, ensuring that I am approachable, value their input, share information openly, and encourage honest feedback to foster a collaborative and transparent environment.', 'required' => true, 'order' => 0, 'active' => true],
//            ['node_id' => 5, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Manage with civility and compassion', 'text' => 'I listen and respond to colleagues and patients’ concerns with interest, care, and professionalism.', 'required' => true, 'order' => 0, 'active' => true],
//
//            ['node_id' => 6, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Create a sense of engagement', 'text' => 'I foster engagement by clarifying roles, valuing contributions, supporting development, and celebrating successes, including learning from challenges.', 'required' => true, 'order' => 0, 'active' => true],
//            ['node_id' => 6, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Support people to feel safe in the workplace', 'text' => 'I identify and remove hazards which could cause harm to myself or others, by dynamically assessing if it is safe to act, considering both physical and psychological safety, and reporting issues, concerns and incidents, including near-misses, promptly.', 'required' => true, 'order' => 0, 'active' => true],
//            ['node_id' => 6, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Managing challenging circumstances', 'text' => 'I approach challenging circumstances calmly, by actively listening to ensure understanding, and seeking help or advice if required, to promptly address difficult situation', 'required' => true, 'order' => 0, 'active' => true],
//
//            ['node_id' => 7, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Provide clear purpose, vision and deliverables', 'text' => 'I provide a clear sense of purpose, direction and realistic operational deliverables, anticipating future trends and challenges, ensuring everyone understands how their role contributes to team and departmental objectives, broader organisational goals, and the future of the NHS, including the 10-year plan.', 'required' => true, 'order' => 0, 'active' => true],
//            ['node_id' => 7, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Manage and measure performance', 'text' => 'I manage and measure the performance of colleagues within my areas of responsibility, ensuring provision of constructive feedback, celebrating achievements, and addressing underperformance in a timely and compassionate manner.', 'required' => true, 'order' => 0, 'active' => true],
//            ['node_id' => 7, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Manage conflict and sensitive conversations', 'text' => 'I approach challenging and sensitive conversations with professionalism and composure, actively listening to all parties and identifying mechanisms, including expert support and advice to de-escalate potential or actual conflict.', 'required' => true, 'order' => 0, 'active' => true],
//
//            ['node_id' => 8, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Allocate and optimise resources', 'text' => 'I understand the importance of optimising resources for current and future needs, working collaboratively to identify opportunities for improvement and digitalisation, and maximise effectiveness and efficiency.', 'required' => true, 'order' => 0, 'active' => true],
//            ['node_id' => 8, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Manage public money effectively', 'text' => 'I recognise that I work in a publicly funded organisation and ensure that my actions future-proof the NHS, by seeking ways to deliver financial accountability, reduce waste, optimise efficiency, sustainability, value for money, and responsible spending.', 'required' => true, 'order' => 0, 'active' => true],
//            ['node_id' => 8, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Use data, evidence and critical thinking', 'text' => 'I use appropriate digital tools to interrogate data and evidence, including subject matter experts, applying findings to identify opportunities which future-proof the service, and enable my areas of responsibility to be more innovative, efficient, productive, and sustainable.', 'required' => true, 'order' => 0, 'active' => true],
//
//            ['node_id' => 9, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Respond to patient safety, needs and preferences', 'text' => 'I understand patient uniqueness and diversity, prioritising safety by reporting concerns and incidents, and adapting my approach to individual needs and preferences.', 'required' => true, 'order' => 0, 'active' => true],
//            ['node_id' => 9, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Prioritise people-centred care to deliver a quality service', 'text' => 'I proactively support the reduction of health inequalities and improve patient outcomes by recognising and valuing diversity, building trust, and promoting inclusion to provide care that centres on each person\'s individual needs.', 'required' => true, 'order' => 0, 'active' => true],
//            ['node_id' => 9, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Apply compliance, governance and policy to improve patient outcomes', 'text' => 'I understand and identify the policies and procedures relevant to my areas of responsibility, applying these to ensure best practice and continuously improve patient outcomes.', 'required' => true, 'order' => 0, 'active' => true],
//
//            ['node_id' => 10, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Drive continuous improvement and innovation', 'text' => 'I seek ways to test new ideas, improve processes, and implement continuous improvement solutions, to drive positive change and solve problems within my areas of responsibility.', 'required' => true, 'order' => 0, 'active' => true],
//            ['node_id' => 10, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Transform through technology and innovation', 'text' => 'I use technology and innovation to enhance my areas of responsibility\'s effectiveness and improve patient outcomes, assessing the positive and negative impact of these changes to minimise risk.', 'required' => true, 'order' => 0, 'active' => true],
//            ['node_id' => 10, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Additional notes', 'text' => 'Additional notes or comments regarding "Leading with a mindset of improvement and innovation"', 'required' => true, 'order' => 0, 'active' => true],
//
//            ['node_id' => 11, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Build impactful relationships', 'text' => 'I build positive working relationships with colleagues, patients, citizens and other stakeholders, treating everyone with respect and valuing their input and perspective.', 'required' => true, 'order' => 0, 'active' => true],
//            ['node_id' => 11, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Lead a collaborative team', 'text' => 'I promote collaborative working within my areas of responsibility, actively contributing to cross-team initiatives and ensuring everyone\'s input is heard and valued, to build inclusive, innovative and high-performing working environments and relationships.', 'required' => true, 'order' => 0, 'active' => true],
//            ['node_id' => 11, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Actively share good practice', 'text' => 'I model the sharing of good practice and learning, proactively seeking information and knowledge from others to enhance my understanding and professional development.', 'required' => true, 'order' => 0, 'active' => true],
        ];

        foreach ($questions as $question) {
            Question::firstOrCreate(
                [
                    'node_id' => $question['node_id'],
                    'title' => $question['title'],
                    'text' => $question['text'],
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
