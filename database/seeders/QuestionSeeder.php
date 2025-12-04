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
            ['node_id' => 3,  'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Prioritise for personal productivity', 'text' => 'I prioritise tasks effectively and make sure my team and I have the resources we need to deliver efficiently and on time.', 'required' => true, 'order' => 0, 'active' => true],
            ['node_id' => 4,  'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Develop personal health and wellbeing strategies', 'text' => 'I manage my health and wellbeing. I set realistic goals and seek support when I need it.', 'required' => true, 'order' => 0, 'active' => true],
            ['node_id' => 5,  'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Commit to continuing professional development', 'text' => 'I continuously build skills and knowledge, encouraging peer learning and development, sharing good practice and seeking feedback.', 'required' => true, 'order' => 0, 'active' => true],

            // Developing self → Communicating well (node_ids: 7,8,9)
            ['node_id' => 7,  'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Communicate with clarity and purpose', 'text' => 'I adapt how I communicate to suit the audience and situation – and ask for feedback to check people have understood.', 'required' => true, 'order' => 0, 'active' => true],
            ['node_id' => 8,  'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Encourage open dialogue and feedback', 'text' => 'I help colleagues and patients to speak up. I listen and follow up on what they say.', 'required' => true, 'order' => 0, 'active' => true],
            ['node_id' => 9,  'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Influence, negotiate and manage upwards', 'text' => 'I make sure people’s voices are heard and concerns are escalated, negotiating on their behalf if necessary.', 'required' => true, 'order' => 0, 'active' => true],

            // Developing self → Responsibility and integrity (node_ids: 11,12,13)
            ['node_id' => 11, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Take accountability for my actions', 'text' => 'I take responsibility for my actions and use feedback and self-reflection to help me improve how I act.', 'required' => true, 'order' => 0, 'active' => true],
            ['node_id' => 12, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Be visible, transparent, and present', 'text' => 'I help colleagues and patients to feel included. I share updates, listen to their views and interact with them openly and respectfully.', 'required' => true, 'order' => 0, 'active' => true],
            ['node_id' => 13, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Manage with civility and compassion', 'text' => 'I listen and respond to colleagues’ and patients’ concerns with interest, care, and professionalism.', 'required' => true, 'order' => 0, 'active' => true],

            // Managing people and resources → Building teams (node_ids: 16,17,18)
            ['node_id' => 16, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Build engagement', 'text' => 'I help people feel involved, recognising their strengths, encouraging teamwork, celebrating success and sharing what we’ve learned.', 'required' => true, 'order' => 0, 'active' => true],
            ['node_id' => 17, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Make sure people feel safe in the workplace', 'text' => 'I make sure that everyone acts safely, feels safe to speak up, and can identify risks and safety training needs.', 'required' => true, 'order' => 0, 'active' => true],
            ['node_id' => 18, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Manage challenges', 'text' => 'I support colleagues in challenging situations – encouraging them to focus on what they can control during change or crisis.', 'required' => true, 'order' => 0, 'active' => true],

            // Managing people and resources → Performance and delivery (node_ids: 20,21,22)
            ['node_id' => 20, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Provide clear purpose, vision, and deliverables', 'text' => 'I work with others to set team and individual objectives that are embedded into meaningful appraisals and aligned with organisational and national plans and objectives – so that everyone understands how their work fits into the bigger picture.', 'required' => true, 'order' => 0, 'active' => true],
            ['node_id' => 21, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Manage and measure performance', 'text' => 'I help colleagues succeed. I am clear about what’s expected and embed this into meaningful appraisals, giving support, guidance, and development opportunities – and address underperformance when necessary.', 'required' => true, 'order' => 0, 'active' => true],
            ['node_id' => 22, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Manage conflict and sensitive conversations', 'text' => 'I remain calm and respectful when managing difficult conversations. I balance openness with sensitivity and seek expert advice and support when needed.', 'required' => true, 'order' => 0, 'active' => true],

            // Managing people and resources → Efficiency and effectiveness (node_ids: 24,25,26)
            ['node_id' => 24, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Allocate and optimise the use of resources', 'text' => 'I use resources effectively, delivering objectives, meeting financial targets and deadlines, and escalating risks.', 'required' => true, 'order' => 0, 'active' => true],
            ['node_id' => 25, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Maximise outputs and get best value for public money', 'text' => 'I demonstrate financial awareness. I use digital tools to spot savings and cut waste, making sure we stay within budget and give good value for public money.', 'required' => true, 'order' => 0, 'active' => true],
            ['node_id' => 26, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Use data, evidence and critical thinking', 'text' => 'I help colleagues use data, evidence, digital tools, colleagues’ expertise, and their own judgement to make evidence‑based decisions.', 'required' => true, 'order' => 0, 'active' => true],

            // Delivering across health and care → Improving quality (node_ids: 29,30,31)
            ['node_id' => 29, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Respond to patient safety concerns, needs and preferences', 'text' => 'I promote listening to patients’ needs, preferences, feedback and safety concerns to improve patient experiences.', 'required' => true, 'order' => 0, 'active' => true],
            ['node_id' => 30, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Personalise care', 'text' => 'I help build the inclusive cultures and services needed to deliver equitable personalised care, ensuring people feel valued and heard.', 'required' => true, 'order' => 0, 'active' => true],
            ['node_id' => 31, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Implement policies and ensure good governance', 'text' => 'I make sure my team and I follow all relevant policies and procedures – and understand how they affect patient care.', 'required' => true, 'order' => 0, 'active' => true],

            // Delivering across health and care → Innovation and improvement (node_ids: 33,34,35)
            ['node_id' => 33, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Drive continuous improvement and innovation', 'text' => 'I encourage colleagues to be curious and try out new ideas to improve how we work.', 'required' => true, 'order' => 0, 'active' => true],
            ['node_id' => 34, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Transform through technology and innovation', 'text' => 'I help others build their digital skills. I encourage them to take the time to try out and adopt new tools.', 'required' => true, 'order' => 0, 'active' => true],
            ['node_id' => 35, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Support others through change', 'text' => 'I support colleagues and patients through change, listening to their concerns and offering reassurance and information.', 'required' => true, 'order' => 0, 'active' => true],

            // Delivering across health and care → Working collaboratively (node_ids: 37,38,39) — corrected
            ['node_id' => 37, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Build relationships', 'text' => 'I build and maintain relationships with colleagues and external partners, valuing diverse perspectives and encouraging collaboration.', 'required' => true, 'order' => 0, 'active' => true],
            ['node_id' => 38, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Lead a collaborative team', 'text' => 'I encourage teamwork and shared decision making, ensuring everyone has a chance to take part.', 'required' => true, 'order' => 0, 'active' => true],
            ['node_id' => 39, 'scale_id' => 1, 'response_type' => ResponseType::TYPE_SCALE, 'title' => 'Share good practice', 'text' => 'I regularly share lessons learned, offering practical advice to develop others and support team working.', 'required' => true, 'order' => 0, 'active' => true],
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
