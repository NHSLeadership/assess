<?php

namespace Database\Seeders;

use App\Models\QuestionVariant;
use Illuminate\Database\Seeder;

class QuestionVariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $variants = [
            ['question_id' => 1, 'text' => 'I prioritise tasks effectively and make sure my team and I have the resources we need to deliver efficiently and on time.', 'rater_type' => null, 'priority' => 0],
            ['question_id' => 1, 'text' => 'I deliver efficiently and on time across teams by planning ahead, managing risks, adjusting priorities, delegating, and managing upwards when necessary.', 'rater_type' => null, 'priority' => 0],
            ['question_id' => 1, 'text' => 'I manage and align complex and evolving priorities across my areas of responsibility to ensure timely and cost-effective delivery.', 'rater_type' => null, 'priority' => 0],
            ['question_id' => 1, 'text' => 'I champion a strategic approach to prioritising work and resources to drive sustainable and efficient improvement.', 'rater_type' => null, 'priority' => 0],

            ['question_id' => 2, 'text' => 'I manage my health and wellbeing. I set realistic goals and seek support when I need it.', 'rater_type' => null, 'priority' => 0],
            ['question_id' => 2, 'text' => 'I make sure wellbeing is prioritised. I promote conversations about a healthy work culture and encourage others to prioritise their health and wellbeing.', 'rater_type' => null, 'priority' => 0],
            ['question_id' => 2, 'text' => 'I lead on building strategies for colleague health and wellbeing, removing barriers and ensuring colleagues have access to appropriate support and resources.', 'rater_type' => null, 'priority' => 0],
            ['question_id' => 2, 'text' => 'I make sure our culture, policies and leadership practices support physical and psychological health and wellbeing for everyone.', 'rater_type' => null, 'priority' => 0],

            ['question_id' => 3, 'text' => 'I continuously build skills and knowledge. I encourage participation in peer learning and development, sharing good practice and seeking feedback.', 'rater_type' => null, 'priority' => 0],
            ['question_id' => 3, 'text' => 'I regularly seek feedback on my management and leadership, pursue coaching and mentoring opportunities, and continually build my skills, knowledge and understanding of good practice.', 'rater_type' => null, 'priority' => 0],
            ['question_id' => 3, 'text' => 'I model and promote skills and knowledge development. I support learning, curiosity, innovation and the adoption of good practice.', 'rater_type' => null, 'priority' => 0],
            ['question_id' => 3, 'text' => 'I support regular reflection on leadership practices – by myself and others – to help us understand what’s working and what could be improved. I assess the organisational impact of learning and development initiatives and help build a culture that supports learning and improvement.', 'rater_type' => null, 'priority' => 0],

            ['question_id' => 4, 'text' => 'I adapt how I communicate to suit the audience and situation – and ask for feedback to check people have understood.', 'rater_type' => null, 'priority' => 0],
            ['question_id' => 4, 'text' => 'I communicate with clarity and purpose, including on difficult issues, considering how my words and actions impact on diverse audiences and wider messaging.', 'rater_type' => null, 'priority' => 0],
            ['question_id' => 4, 'text' => 'I am flexible and inclusive in the way I communicate, and I support others to do the same.', 'rater_type' => null, 'priority' => 0],
            ['question_id' => 4, 'text' => 'I communicate complex information in a way that everyone can understand and build a culture of effective and inclusive communication across the organisation.', 'rater_type' => null, 'priority' => 0],

            ['question_id' => 5, 'text' => 'I help colleagues and patients to speak up. I listen and follow up on what they say.', 'rater_type' => null, 'priority' => 0],
            ['question_id' => 5, 'text' => 'I help create a safe and inclusive environment -encouraging open dialogue, supporting people to speak up and responding to concerns.', 'rater_type' => null, 'priority' => 0],
            ['question_id' => 5, 'text' => 'I lead by example and invite feedback on my actions from staff and patients – creating space for honesty without fear of consequences.', 'rater_type' => null, 'priority' => 0],
            ['question_id' => 5, 'text' => 'I promote a culture of open communication and ‘speaking up’ across the organisation, embedding accountability at all levels.', 'rater_type' => null, 'priority' => 0],

            ['question_id' => 6, 'text' => 'I make sure people’s voices are heard and concerns are escalated, negotiating on their behalf if necessary.', 'rater_type' => null, 'priority' => 0],
            ['question_id' => 6, 'text' => 'I advocate for others, using their expertise and experience to negotiate mutually beneficial outcomes.', 'rater_type' => null, 'priority' => 0],
            ['question_id' => 6, 'text' => 'I influence and negotiate across functions or systems, acting as an advocate to improve outcomes and experiences for all.', 'rater_type' => null, 'priority' => 0],
            ['question_id' => 6, 'text' => 'I am an advocate for my organisation using evidence to influence local, national, and political decision makers.', 'rater_type' => null, 'priority' => 0],
        ];
        foreach ($variants as $variant) {
            QuestionVariant::firstOrCreate(
                [
                    'question_id' => $variant['question_id'],
                    'text' => $variant['text'],
                    'rater_type' => $variant['rater_type'],
                    'priority' => $variant['priority'],
                ],
            );
        }
    }
}
