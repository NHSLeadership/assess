<?php

namespace Database\Seeders;

use App\Models\QuestionVariant;
use Illuminate\Database\Seeder;

class QuestionVariantSeeder extends Seeder
{
    public function run(): void
    {
        $variants = [
            // Question 1: Prioritise for personal productivity
            ['question_id' => 1, 'text' => 'I prioritise tasks effectively and make sure my team and I have the resources we need to deliver efficiently and on time.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 1, 'text' => 'I deliver efficiently and on time across teams, planning ahead, managing risks, adjusting priorities, delegating, and managing upwards.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 1, 'text' => 'I manage and align complex and evolving priorities to ensure timely and cost-effective delivery.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 1, 'text' => 'I champion a strategic approach to prioritising work and resources to drive sustainable and efficient improvement.', 'rater_type' => 'self', 'priority' => 0],

            // Question 2: Develop personal health and wellbeing strategies
            ['question_id' => 2, 'text' => 'I manage my health and wellbeing. I set realistic goals and seek support when I need it.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 2, 'text' => 'I make sure wellbeing is prioritised, promoting a healthy work culture and encouraging others to prioritise their health and wellbeing.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 2, 'text' => 'I lead on building strategies for colleague health and wellbeing, removing barriers and ensuring colleagues have access to appropriate support and resources.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 2, 'text' => 'I make sure our culture, policies and leadership practices support physical and psychological health and wellbeing for everyone.', 'rater_type' => 'self', 'priority' => 0],

            // Question 3: Commit to continuing professional development
            ['question_id' => 3, 'text' => 'I continuously build skills and knowledge, encouraging peer learning and development, sharing good practice and seeking feedback.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 3, 'text' => 'I regularly seek feedback on my management and leadership, pursue coaching and mentoring opportunities, and continually build my skills, knowledge and understanding of good practice.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 3, 'text' => 'I model and promote skills and knowledge development, supporting learning, curiosity, innovation and the adoption of good practice.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 3, 'text' => 'I support regular reflection on leadership practices – by myself and others – to help us understand what’s working and what could be improved. I assess the organisational impact of learning and development initiatives and help build a culture that supports learning and improvement.', 'rater_type' => 'self', 'priority' => 0],

            // Question 4: Communicate with clarity and purpose
            ['question_id' => 4, 'text' => 'I adapt how I communicate to suit the audience and situation – and ask for feedback to check people have understood.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 4, 'text' => 'I communicate with clarity and purpose, including on difficult issues, considering how words and actions impact on diverse audiences and wider messaging.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 4, 'text' => 'I am flexible and inclusive in the way I communicate, and I support others to do the same.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 4, 'text' => 'I communicate complex information in a way that everyone can understand and build a culture of effective and inclusive communication across the organisation.', 'rater_type' => 'self', 'priority' => 0],

            // Question 5: Encourage open dialogue and feedback
            ['question_id' => 5, 'text' => 'I help colleagues and patients to speak up, listening and following up on what they say.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 5, 'text' => 'I help create a safe and inclusive environment – encouraging open dialogue, supporting people to speak up and responding to concerns.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 5, 'text' => 'I lead by example and invite feedback from staff and patients – creating space for honesty without fear of consequences.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 5, 'text' => 'I promote a culture of open communication and ‘speaking up’ across the organisation, embedding accountability at all levels.', 'rater_type' => 'self', 'priority' => 0],

            // Question 6: Influence, negotiate and manage upwards
            ['question_id' => 6, 'text' => 'I make sure people’s voices are heard and concerns are escalated, negotiating on their behalf if necessary.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 6, 'text' => 'I advocate for others, using their expertise and experience to negotiate mutually beneficial outcomes.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 6, 'text' => 'I influence and negotiate across functions or systems, acting as an advocate to improve outcomes and experiences for all.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 6, 'text' => 'I am an advocate for my organisation using evidence to influence local, national, and political decision makers.', 'rater_type' => 'self', 'priority' => 0],

            // Question 7: Take accountability for my actions
            ['question_id' => 7, 'text' => 'I take responsibility for my actions and use feedback and self-reflection to help me improve how I act.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 7, 'text' => 'I establish clear lines of accountability within or across teams and challenge any inappropriate behaviour and failure to deal with it.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 7, 'text' => 'I take responsibility for my team’s actions, make sure we are fair and inclusive, and challenge inappropriate behaviour and any failure to address it.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 7, 'text' => 'I shape the organisation’s culture and use up-to-date knowledge and insight to embed inclusivity and ethical approaches into policies, processes and practice.', 'rater_type' => 'self', 'priority' => 0],

            // Question 8: Be visible, transparent, and present
            ['question_id' => 8, 'text' => 'I help colleagues and patients to feel included — sharing updates, listening to their views and interacting openly and respectfully.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 8, 'text' => 'I avoid distractions and give colleagues and patients my full attention, showing that I value their input.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 8, 'text' => 'I am readily accessible and visible to others, joining team and stakeholder meetings and communicating frequently through different channels.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 8, 'text' => 'I model being visible and transparent, keep colleagues and stakeholders well‑informed through regular communication and explain the rationale for decisions.', 'rater_type' => 'self', 'priority' => 0],

            // Question 9: Manage with civility and compassion
            ['question_id' => 9, 'text' => 'I listen and respond to colleagues’ and patients’ concerns with interest, care, and professionalism.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 9, 'text' => 'I balance openness with sensitivity when resolving difficult situations and use supportive, developmental conversations to improve performance.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 9, 'text' => 'I promote compassionate and inclusive leadership, including identifying and calling out discourteous or inappropriate behaviour.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 9, 'text' => 'I promote a workplace culture that is kind and respectful, where people thrive and enjoy coming to work and the quality of services improves.', 'rater_type' => 'self', 'priority' => 0],

            // Question 10: Build engagement
            ['question_id' => 10, 'text' => 'I help people feel involved, recognising their strengths, encouraging teamwork, celebrating success and sharing what we’ve learned.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 10, 'text' => 'I create an environment where people feel valued, sharing successes and lessons learned across teams to increase engagement.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 10, 'text' => 'I foster a culture of respect and creativity that motivates people and values everyone’s ideas.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 10, 'text' => 'I promote an inclusive culture, helping people apply their different skills, expertise and perspectives effectively; I help attract and retain talent by celebrating our successes.', 'rater_type' => 'self', 'priority' => 0],

            // Question 11: Make sure people feel safe in the workplace
            ['question_id' => 11, 'text' => 'I make sure that everyone acts safely, feels safe to speak up, and can identify risks and safety training needs.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 11, 'text' => 'I assess risks and maintain risk registers, making sure that physical and psychological safety issues and incidents are reported and responded to, trends are identified, and lessons learned are disseminated.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 11, 'text' => 'I implement appropriate safety policies, making sure everyone understands their role in achieving a safe working environment and has access to safety training.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 11, 'text' => 'I build a learning culture that puts safety first, takes whistleblowing seriously, and prioritises safe workplaces and practices at all times.', 'rater_type' => 'self', 'priority' => 0],

            // Question 12: Manage challenges
            ['question_id' => 12, 'text' => 'I support colleagues in challenging situations — encouraging them to focus on what they can control during change or crisis.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 12, 'text' => 'I handle challenges in my area of responsibility. I listen and gather feedback, identify solutions, and escalate upwards if required.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 12, 'text' => 'I manage complex challenges — spotting risks early, setting out clear expectations, and empowering others to identify, develop, implement and evaluate solutions.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 12, 'text' => 'I navigate organisational and national challenges — identifying and tackling risks proactively, communicating clearly, and building a resilient, “can‑do” culture.', 'rater_type' => 'self', 'priority' => 0],

            // Question 13: Provide clear purpose, vision, and deliverables
            ['question_id' => 13, 'text' => 'I work with others to set team and individual objectives aligned to the strategic plan, so that everyone understands how their work fits into the bigger picture.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 13, 'text' => 'I communicate the organisation’s vision, strategic plan, objectives and performance measures, so people understand their roles and how they can contribute.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 13, 'text' => 'I help to articulate a clear vision for the organisation and work with senior leaders to turn national goals into practical plans with measurable deliverables.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 13, 'text' => 'I work with senior leaders across the sector to build a shared vision and purpose, providing innovative plans with clear goals and outcome measures.', 'rater_type' => 'self', 'priority' => 0],

            // Question 14: Manage and measure performance
            ['question_id' => 14, 'text' => 'I help colleagues succeed, providing clarity about what’s expected and embedding this into meaningful appraisals — giving support, guidance, development opportunities and addressing underperformance when necessary.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 14, 'text' => 'I build high performing teams — regularly sharing and learning from feedback, undertaking meaningful appraisals and managing underperformance proactively to balance accountability with growth.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 14, 'text' => 'I embed high performance across functions or systems — tracking progress, measuring impact, managing risk, embedding quality appraisals and performance management for all.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 14, 'text' => 'I establish a high‑performance culture and align appraisal and performance management approaches across the organisation, ensuring they support fair, equitable and transparent processes for all.', 'rater_type' => 'self', 'priority' => 0],

            // Question 15: Manage conflict and sensitive conversations
            ['question_id' => 15, 'text' => 'I remain calm and respectful when managing difficult conversations — balancing openness with sensitivity and seeking expert advice and support when needed.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 15, 'text' => 'I identify and address underlying issues that lead to conflict, supporting and guiding colleagues, and involving others when wider action is needed.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 15, 'text' => 'I oversee conflict management — keeping communication open, offering guidance and support and engaging experts to help deal with complex or serious cases.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 15, 'text' => 'I build a culture of open, honest, and respectful communication, making sure we follow good HR good practice and policies.', 'rater_type' => 'self', 'priority' => 0],

            // Question 16: Allocate and optimise the use of resources
            ['question_id' => 16, 'text' => 'I use resources effectively, delivering objectives, meeting financial targets and deadlines, and escalating risks.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 16, 'text' => 'I make best use of money, people, and technology across my areas of responsibility, managing risks while maintaining focus on the organisation’s objectives.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 16, 'text' => 'I plan ahead to make sure we have the right, sustainable resources that support our strategic objectives and wider system plans.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 16, 'text' => 'I make sure we have robust and adaptable governance to manage risk, break down financial silos, and support efficient and sustainable delivery across the system.', 'rater_type' => 'self', 'priority' => 0],

            // Question 17: Maximise outputs and get best value for public money
            ['question_id' => 17, 'text' => 'I demonstrate financial awareness, spotting savings, cutting waste, staying within budget and giving good value for public money.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 17, 'text' => 'I oversee finances and budgets, flagging funding concerns early and looking for savings and opportunities to remove waste so we can protect services and patient care.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 17, 'text' => 'I lead strategic financial planning and budget allocation, considering system-wide impacts and managing financial risks to ensure quality and protect service delivery.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 17, 'text' => 'I set the strategy for managing public money and make sure we have the financial controls, governance and accountability we need to deliver value for money and long-term sustainability.', 'rater_type' => 'self', 'priority' => 0],

            // Question 18: Use data, evidence and critical thinking
            ['question_id' => 18, 'text' => 'I help colleagues use data, evidence, digital tools, others’ expertise, and their own judgement to make evidence‑based decisions.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 18, 'text' => 'I build a culture where people use data, evidence, digital tools and critical thinking to improve service sustainability and efficiency.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 18, 'text' => 'I champion the use of data, evidence, digital tools and critical thinking to design more accessible, inclusive, and sustainable services.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 18, 'text' => 'I ensure evidence‑driven strategies are embedded at all levels to improve decision‑making and to future‑proof high quality services.', 'rater_type' => 'self', 'priority' => 0],

            // Question 19: Respond to patient safety concerns, needs and preferences
            ['question_id' => 19, 'text' => 'I put patients first and report safety concerns and incidents.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 19, 'text' => 'I promote listening to patients’ needs, preferences, feedback and safety concerns to improve patient experiences.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 19, 'text' => 'I champion patient perspectives, listening to patients and families and seeking to understand their needs and preferences. I ensure systems are in place to respond to concerns and incidents.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 19, 'text' => 'I make sure patients’ safety, experience and outcomes are central to all we do, using patient feedback and data to drive improvements.', 'rater_type' => 'self', 'priority' => 0],

            // Question 20: Personalise care
            ['question_id' => 20, 'text' => 'I build trust with individuals and seek to understand their specific needs in order to ensure they get the best outcomes.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 20, 'text' => 'I help build the inclusive culture and services needed to deliver equitable personalised care, ensuring people feel valued and heard.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 20, 'text' => 'I develop and implement processes to deliver equitable personalised care, encouraging all voices to contribute.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 20, 'text' => 'I lead initiatives to address health inequalities, collaborating with patients and stakeholders to share lessons learnt and driving systemic improvements in personalised care.', 'rater_type' => 'self', 'priority' => 0],

            // Question 21: Implement policies and ensure good governance
            ['question_id' => 21, 'text' => 'I understand my organisation’s policies and procedures and use them to ensure best practice.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 21, 'text' => 'I make sure my team and I follow all relevant policies and procedures – and understand how they affect patient care.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 21, 'text' => 'I help put new policies and governance in place, making sure everyone understands why they are being introduced and how to escalate concerns.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 21, 'text' => 'I help develop and implement care policies and governance that meet national standards and regulations and drive service improvement.', 'rater_type' => 'self', 'priority' => 0],

            // Question 22: Drive continuous improvement and innovation
            ['question_id' => 22, 'text' => 'I test new approaches and use continuous improvement methods to solve problems.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 22, 'text' => 'I encourage colleagues to be curious and try out new ideas to improve how we work.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 22, 'text' => 'I lead innovation and improvement projects and help measure and build the organisation’s capability and effectiveness.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 22, 'text' => 'I build a culture of continuous improvement, encouraging team members to work together and to develop, implement and evaluate new approaches.', 'rater_type' => 'self', 'priority' => 0],

            // Question 23: Transform through technology and innovation
            ['question_id' => 23, 'text' => 'I build my digital skills by using new tools and thinking about how they can support my work and the services we provide.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 23, 'text' => 'I help others build their digital skills, encouraging them to try out and adopt new tools.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 23, 'text' => 'I support teams to build their digital skills and investigate new technologies that could improve delivery, within the organisation’s guidelines.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 23, 'text' => 'I make digital skills a priority for everyone, promoting new technologies and ideas aligned with organisational strategies.', 'rater_type' => 'self', 'priority' => 0],

            // Question 24: Support others through change
            ['question_id' => 24, 'text' => 'I embrace organisational change and keep in regular contact with colleagues during change processes.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 24, 'text' => 'I support colleagues and patients through change, listening to their concerns and offering reassurance and information.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 24, 'text' => 'I work across teams to develop change plans — communicating strategic direction clearly and addressing concerns to minimise disruption.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 24, 'text' => 'I lead and embed organisational change, making sure colleagues and patients are supported and engaged, and change is implemented effectively.', 'rater_type' => 'self', 'priority' => 0],

            // Question 25: Build relationships
            ['question_id' => 25, 'text' => 'I build good working relationships by treating people with respect and valuing their input.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 25, 'text' => 'I build and maintain relationships with colleagues and stakeholders, valuing diverse perspectives and encouraging collaboration.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 25, 'text' => 'I create a culture where colleagues and partners support each other and share information that helps improve quality, efficiency and patient and staff satisfaction.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 25, 'text' => 'I build networks and shape key strategic relationships, including with the wider community, to allow us to reach shared goals and deliver improvement.', 'rater_type' => 'self', 'priority' => 0],

            // Question 26: Lead a collaborative team
            ['question_id' => 26, 'text' => 'I work well with others by joining cross‑team projects and building good relationships.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 26, 'text' => 'I encourage teamwork and shared decision making, ensuring everyone has a chance to take part.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 26, 'text' => 'I build and lead collaborative teams and help colleagues use their ideas and expertise to drive improvement.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 26, 'text' => 'I promote collaboration across functions, making sure teams share responsibility, trust each other, and value different perspectives.', 'rater_type' => 'self', 'priority' => 0],

            // Question 27: Share good practice
            ['question_id' => 27, 'text' => 'I seek out good practice and new ideas that will help me do my job better.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 27, 'text' => 'I regularly share lessons learned, offering practical advice to develop others and support team working.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 27, 'text' => 'I encourage colleagues to identify and share good practice within and beyond the team to promote the learning of others.', 'rater_type' => 'self', 'priority' => 0],
            ['question_id' => 27, 'text' => 'I lead initiatives to share good practice, lessons learned and innovations across the organisation to improve the quality of everything we do.', 'rater_type' => 'self', 'priority' => 0],
        ];

        foreach ($variants as $variant) {
            QuestionVariant::firstOrCreate([
                'question_id' => $variant['question_id'],
                'text'        => $variant['text'],
                'rater_type'  => $variant['rater_type'],
                'priority'    => $variant['priority'],
            ]);
        }
    }
}
