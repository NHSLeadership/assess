<?php

namespace Database\Seeders;

use App\Models\Framework;
use Illuminate\Database\Seeder;

class FrameworkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $report_intro = '<h2>Introduction</h2><p>This personalised report identifies your management and leadership strengths and guides your personal development. It presents your self-ratings against the Management and<br>Leadership Framework competencies.</p><hr><h2>Reminder of the rating scale</h2><p>You were asked to consider the extent to which you demonstrate each competence in your<br>work, using the following capability scale:</p><p>1. <strong>Insufficient</strong> – I can’t do this without help.</p><p>This means you do not yet meet the required standard</p><ul><li><p>Limited or minimal evidence of the competence in day-to-day work.</p></li><li><p>May indicate a lack of familiarity, confidence, understanding or opportunity to apply<br>the competence.</p></li></ul><p>2. <strong>Essential</strong> – I can do parts of this but need guidance.</p><p>This means you meet the minimum acceptable standard in routine and predictable situations</p><ul><li><p>Demonstrated reliably in routine situations.</p></li><li><p>Shows an understanding of the core competence but may still require support or<br>development to apply it consistently in more complex scenarios.</p></li></ul><p>3. <strong>Proficient</strong> – I can do most of this with occasional help.</p><p>This means you perform adequately and consistently across a range of situations</p><ul><li><p>Shows confidence and autonomy in most situations.</p></li><li><p>Represents solid, competent practice for much of your role.</p></li></ul><p>4. <strong>Strong</strong> – I can do all of this independently.</p><p>This means you consistently perform above the expected standard</p><ul><li><p>Demonstrated across more complex, challenging or unpredictable situations.</p></li><li><p>Others are likely to benefit from or recognise your strength in this area.</p></li></ul><p>5. <strong>Exemplary</strong> – I always do this well and support others to do this.</p><p>This means you always excel and set the benchmark for performance, role modelling beyond<br>your own remit and developing others</p><ul><li><p>Demonstrated consistently and with significant positive influence on people, teams, or<br>systems.</p></li><li><p>Represents practice and skills that others may learn from or adopt.</p></li></ul><hr><h2>How to use this report</h2><p><br>This report is a developmental tool designed to support reflection, insight, and focussed<br>management and leadership growth. It should be used as part of your appraisal and<br>developmental conversations with your line manager, noting the following considerations:</p><ul><li><p>Review with reflective curiosity</p><ul><li><p>Approach your results openly. The aim is to understand how you perceive<br>your current strengths and development needs, not to form a judgement about<br>performance.</p></li></ul></li><li><p>Recognise your strengths</p><ul><li><p>Identify where you have rated yourself most confidently. These areas form an<br>important foundation for your leadership practice and can be built upon to<br>enhance your impact.</p></li></ul></li><li><p>Identify priority development areas</p><ul><li><p>Focus on the competencies that will make the greatest difference to your<br>effectiveness and future progression. Not every lower rating requires action—<br>prioritisation is key.</p></li></ul></li><li><p>Shape a focused development plan</p><ul><li><p>Use the insights to select one to three meaningful development priorities.<br>Consider what good practice looks like, the support you may need, and how<br>you will monitor progress.</p></li></ul></li><li><p>Use your report in appraisals and development conversations</p><ul><li><p>Bring your reflections into discussions with your manager or coach. These<br>conversations can help refine priorities, identify opportunities, and clarify next<br>steps.</p></li></ul></li><li><p>Revisit the report periodically</p><ul><li><p>Treat the report as a living reference point. Return to it as your role evolves or<br>after completing development activity to track progress and identify emerging<br>strengths.</p></li></ul></li></ul>';
        $html = '<style>
@media only screen and (max-width: 768px) {
    .three-col-table {
        border-spacing: 0 !important;
    }
    .three-col-table td.three-col-item {
        display: block !important;
        width: 100% !important;
        margin-bottom: 12px !important;
    }
}

/* Desktop/tablet web spacing */
@media screen and (min-width: 769px) {
    .three-col-table {
        border-spacing: 12px !important;
    }
}

.image-adjust {
    max-width: 80%;
    height: auto;
    display: block;
    margin: 0 auto;
}

/* Optional: mobile-specific tweaks */
@media (max-width: 600px) {
    .image-adjust {
        max-width: 90%;
    }
}
</style>
<h2>Management and leadership framework structure: domains, standards and competencies</h2>
<div style="max-height:80%; text-align:center;">
<img src="/media/wheel.png" alt="Framework wheel diagram" data-id="media/DO6CW39pOo9Jp5MEN0BVCqzDJQRhI3CdAUAdNZfh.png" class="image-adjust">
<table class="three-col-table"
       style="transform-origin: top left; max-height:80%; margin-left:auto; margin-right:auto;margin-top:10px;">
    <tr>
        <!-- COLUMN 1 -->
        <td class="three-col-item col-1" style="width: 33%; background-color: #BDDECD; border-radius: 8px; padding: 10px; vertical-align: top;">
            <div class="three-col-inner">
                <h3 class="three-col-title" style="margin: 0 0 6px 0;">Personal Impact</h3>

                <p style="margin: 2px 0 4px 0;"><strong>Personal productivity and wellbeing</strong></p>
                <ul style="margin: 4px 0 6px 18px; padding: 0;">
                    <li>Prioritise for personal productivity</li>
                    <li>Develop personal health and wellbeing strategies</li>
                    <li>Commit to continuing professional development</li>
                </ul>

                <p style="margin: 2px 0 4px 0;"><strong>Communicating well</strong></p>
                <ul style="margin: 4px 0 6px 18px; padding: 0;">
                    <li>Communicate with clarity and purpose</li>
                    <li>Encourage open dialog and feedback</li>
                    <li>Influence, negotiate and manage upwards</li>
                </ul>

                <p style="margin: 2px 0 4px 0;"><strong>Responsibility and integrity</strong></p>
                <ul style="margin: 4px 0 0 18px; padding: 0;">
                    <li>Take accountability for my actions</li>
                    <li>Be visible, transparent and present</li>
                    <li>Manage with civility and compassion</li>
                </ul>
            </div>
        </td>

        <!-- COLUMN 2 -->
        <td class="three-col-item col-2" style="width: 33%; background-color: #DEC9D4; border-radius: 8px; padding: 10px; vertical-align: top;">
            <div class="three-col-inner">
                <h3 class="three-col-title" style="margin: 0 0 6px 0;">Managing People and Resources</h3>

                <p style="margin: 2px 0 4px 0;"><strong>Building teams</strong></p>
                <ul style="margin: 4px 0 6px 18px; padding: 0;">
                    <li>Build engagement</li>
                    <li>Make sure people feel safe in the workplace</li>
                    <li>Manage challenges</li>
                </ul>

                <p style="margin: 2px 0 4px 0;"><strong>Performance and Delivery</strong></p>
                <ul style="margin: 4px 0 6px 18px; padding: 0;">
                    <li>Provide clear purpose, vision and deliverables</li>
                    <li>Manage and measure performance</li>
                    <li>Manage conflict and sensitive conversations</li>
                </ul>

                <p style="margin: 2px 0 4px 0;"><strong>Efficiency and effectiveness</strong></p>
                <ul style="margin: 4px 0 0 18px; padding: 0;">
                    <li>Allocate and optimise the use of resources</li>
                    <li>Maximise outputs and get best value for public money</li>
                    <li>Use data, evidence and critical thinking</li>
                </ul>
            </div>
        </td>

        <!-- COLUMN 3 -->
        <td class="three-col-item col-3" style="width: 33%; background-color: #BECBE1; border-radius: 8px; padding: 10px; vertical-align: top;">
            <div class="three-col-inner">
                <h3 class="three-col-title" style="margin: 0 0 6px 0;">Delivering Across Health and Care</h3>

                <p style="margin: 2px 0 4px 0;"><strong>Improving quality</strong></p>
                <ul style="margin: 4px 0 6px 18px; padding: 0;">
                    <li>Respond to patient safety concerns, needs and preferences</li>
                    <li>Personalise care</li>
                    <li>Implement policies and ensure good governance</li>
                </ul>

                <p style="margin: 2px 0 4px 0;"><strong>Innovation and improvement</strong></p>
                <ul style="margin: 4px 0 6px 18px; padding: 0;">
                    <li>Drive continuous improvement and innovation</li>
                    <li>Transform through technology and innovation</li>
                    <li>Support others through change</li>
                </ul>

                <p style="margin: 2px 0 4px 0;"><strong>Working collaboratively</strong></p>
                <ul style="margin: 4px 0 0 18px; padding: 0;">
                    <li>Build relationships</li>
                    <li>Lead a collaborative team</li>
                    <li>Share good practice</li>
                </ul>
            </div>
        </td>
    </tr>
</table>

</div>';
        $report_ending = '<h3>Reflection and planning space</h3>
<ul>
    <li>The following questions may help you reflect and plan.</li>
</ul>

<h3>Appraisal questions</h3>
<ul>
    <li>What would I like to discuss with my manager?</li>
    <li>When is it harder to demonstrate my strengths? What do others see?</li>
    <li>What gets in the way of addressing my development areas and how these can be overcome?</li>
    <li>What actions am I thinking about taking to develop my leadership and management?</li>
</ul>

<h3>Development planning</h3>
<ul>
    <li>What are my priority development areas?</li>
    <li>How does it link to my career aspirations?</li>
    <li>How do these feed into my Personal Development plan?</li>
    <li>How will I achieve my learning needs?</li>
    <li>Who can help me with my development?</li>
    <li>What are my timeframes?</li>
</ul>
';
        Framework::firstOrCreate([
            'name'        => 'Management and leadership framework',
            'slug'        => 'mlf',
            'description' => '<p>The framework consists of a code of practice, standards and competencies at all levels from entry level manager to executive, and a learning and development curriculum.</p>',
            'instructions' => '<h2>Welcome to your NHS Management and Leadership assessment</h2><p>In this assessment you will be presented with a series of questions.</p><p>To get the most value from this exercise answer these questions as accurately and honestly as you can.</p>',
            'report_intro' => $report_intro,
            'report_html' => $html,
            'report_ending' => $report_ending,
        ]);
    }
}
