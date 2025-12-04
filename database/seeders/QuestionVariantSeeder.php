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

            ['question_id' => 7, 'text' => 'I take responsibility for my actions and use feedback and self-reflection to help me improve how I act.', 'rater_type' => null, 'priority' => 0],
            ['question_id' => 7, 'text' => 'I establish clear lines of accountability within or across teams and challenge inappropriate behaviour, as well as any failure to deal with it.', 'rater_type' => null, 'priority' => 0],
            ['question_id' => 7, 'text' => 'I take responsibility for my team’s actions, make sure we are fair and inclusive, and challenge inappropriate behaviour and any failure to address it.', 'rater_type' => null, 'priority' => 0],
            ['question_id' => 7, 'text' => 'I shape the organisation’s culture and use up-to-date knowledge and insight to embed inclusivity and ethical approaches into policies, processes and practice.', 'rater_type' => null, 'priority' => 0],

            ['question_id' => 8, 'text' => 'I help colleagues and patients to feel included. I share updates, listen to their views and interact with them openly and respectfully', 'rater_type' => null, 'priority' => 0],
            ['question_id' => 8, 'text' => 'I avoid distractions and give colleagues and patients my full attention, showing that I value their input.', 'rater_type' => null, 'priority' => 0],
            ['question_id' => 8, 'text' => 'I am readily accessible and visible to others, joining team and stakeholder meetings and communicating frequently through different channels.', 'rater_type' => null, 'priority' => 0],
            ['question_id' => 8, 'text' => 'I model being visible and transparent, keep colleagues and stakeholders well-informed through regular communication and explain the rationale for decisions.', 'rater_type' => null, 'priority' => 0],

            ['question_id' => 9, 'text' => 'I listen and respond to colleagues and patients’ concerns with interest, care, and professionalism.', 'rater_type' => null, 'priority' => 0],
            ['question_id' => 9, 'text' => 'I balance openness with sensitivity when resolving difficult situations. I use supportive, developmental conversations to improve performance.', 'rater_type' => null, 'priority' => 0],
            ['question_id' => 9, 'text' => 'I promote compassionate and inclusive leadership, including identifying and calling out discourteous or inappropriate behaviour.', 'rater_type' => null, 'priority' => 0],
            ['question_id' => 9, 'text' => 'I promote a workplace culture that is kind and respectful, where people thrive and enjoy coming to work and the quality of services improves.', 'rater_type' => null, 'priority' => 0],


            ['question_id' => 10, 'text' => 'I help people feel involved. I recognise their strengths, encourage teamwork, celebrate success and share what we’ve learned.', 'rater_type' => null, 'priority' => 0],
            ['question_id' => 10, 'text' => 'I create an environment where people feel valued and safe. I share successes and lessons learned across teams to increase engagement.', 'rater_type' => null, 'priority' => 0],
            ['question_id' => 10, 'text' => 'I foster a culture of respect and creativity that motivates people and values everyone’s ideas.', 'rater_type' => null, 'priority' => 0],
            ['question_id' => 10, 'text' => 'I promote an inclusive culture, helping people apply their different skills, expertise and perspectives effectively. I help attract and retain talent by celebrating our successes.', 'rater_type' => null, 'priority' => 0],

            ['question_id' => 11, 'text' => 'I make sure that everyone acts safely, feels safe to speak up, and can identify risks and safety training needs.', 'rater_type' => null, 'priority' => 0],
            ['question_id' => 11, 'text' => 'I assess risks and maintain risk registers, making sure that physical and psychological safety issues and incidents are reported and responded to, trends are identified, and lessons learned are disseminated.', 'rater_type' => null, 'priority' => 0],
            ['question_id' => 11, 'text' => 'I implement appropriate safety policies, making sure everyone understands their role in achieving a safe working environment and has access to safety training.', 'rater_type' => null, 'priority' => 0],
            ['question_id' => 11, 'text' => 'I build a learning culture that puts safety first, takes whistleblowing seriously, and prioritises safe workplaces and practices at all times.', 'rater_type' => null, 'priority' => 0],

            ['question_id' => 12, 'text' => 'I support colleagues in challenging situations - encouraging them to focus on what they can control during change or crisis.', 'rater_type' => null, 'priority' => 0],
            ['question_id' => 12, 'text' => 'I handle challenges in my area of responsibility. I listen and gather feedback, identify solutions, and escalate upwards if required.', 'rater_type' => null, 'priority' => 0],
            ['question_id' => 12, 'text' => 'I manage complex challenges – spotting risks early, setting out clear expectations, and empowering others to identify, develop, implement, and evaluate solutions.', 'rater_type' => null, 'priority' => 0],
            ['question_id' => 12, 'text' => 'I navigate organisational and national challenges – identifying and tackling the risks proactively, communicating clearly, and building a resilient, \'can-do\' culture.', 'rater_type' => null, 'priority' => 0],
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
