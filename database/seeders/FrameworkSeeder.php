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
</style>

<table class="three-col-table" style="width: 100%; border-collapse: separate; border-spacing: 12px; table-layout: auto; margin: 0; padding: 0;">
    <tr>
        <!-- COLUMN 1 -->
        <td class="three-col-item col-1" style="width: 33%; background-color: #E6F4FF; border-radius: 8px; padding: 10px; vertical-align: top;">
            <div class="three-col-inner">
                <h3 class="three-col-title" style="margin: 0 0 8px 0;">Personal Impact</h3>

                <p style="margin: 0 0 6px 0;"><strong>Personal productivity and wellbeing</strong></p>
                <ul style="margin: 0 0 12px 18px; padding: 0;">
                    <li>Prioritise for personal productivity</li>
                    <li>Develop personal health and wellbeing strategies</li>
                    <li>Commit to continuing professional development</li>
                </ul>

                <p style="margin: 0 0 6px 0;"><strong>Communicating well</strong></p>
                <ul style="margin: 0 0 12px 18px; padding: 0;">
                    <li>Communicate with clarity and purpose</li>
                    <li>Encourage open dialog and feedback</li>
                    <li>Influence, negotiate and manage upwards</li>
                </ul>

                <p style="margin: 0 0 6px 0;"><strong>Responsibility and integrity</strong></p>
                <ul style="margin: 0 0 0 18px; padding: 0;">
                    <li>Take accountability for my actions</li>
                    <li>Be visible, transparent and present</li>
                    <li>Manage with civility and compassion</li>
                </ul>
            </div>
        </td>

        <!-- COLUMN 2 -->
        <td class="three-col-item col-2" style="width: 33%; background-color: #FFF4E6; border-radius: 8px; padding: 10px; vertical-align: top;">
            <div class="three-col-inner">
                <h3 class="three-col-title" style="margin: 0 0 8px 0;">Managing People and Resources</h3>

                <p style="margin: 0 0 6px 0;"><strong>Building teams</strong></p>
                <ul style="margin: 0 0 12px 18px; padding: 0;">
                    <li>Build engagement</li>
                    <li>Make sure people feel safe in the workplace</li>
                    <li>Manage challenges</li>
                </ul>

                <p style="margin: 0 0 6px 0;"><strong>Performance and Delivery</strong></p>
                <ul style="margin: 0 0 12px 18px; padding: 0;">
                    <li>Provide clear purpose, vision and deliverables</li>
                    <li>Manage and measure performance</li>
                    <li>Manage conflict and sensitive conversations</li>
                </ul>

                <p style="margin: 0 0 6px 0;"><strong>Efficiency and effectiveness</strong></p>
                <ul style="margin: 0 0 0 18px; padding: 0;">
                    <li>Allocate and optimise the use of resources</li>
                    <li>Maximise outputs and get best value for public money</li>
                    <li>Use data, evidence and critical thinking</li>
                </ul>
            </div>
        </td>

        <!-- COLUMN 3 -->
        <td class="three-col-item col-3" style="width: 33%; background-color: #E6FFE6; border-radius: 8px; padding: 10px; vertical-align: top;">
            <div class="three-col-inner">
                <h3 class="three-col-title" style="margin: 0 0 8px 0;">Delivering Across Health and Care</h3>

                <p style="margin: 0 0 6px 0;"><strong>Improving quality</strong></p>
                <ul style="margin: 0 0 12px 18px; padding: 0;">
                    <li>Respond to patient safety concerns, needs and preferences</li>
                    <li>Personalise care</li>
                    <li>Implement policies and ensure good governance</li>
                </ul>

                <p style="margin: 0 0 6px 0;"><strong>Innovation and improvement</strong></p>
                <ul style="margin: 0 0 12px 18px; padding: 0;">
                    <li>Drive continuous improvement and innovation</li>
                    <li>Transform through technology and innovation</li>
                    <li>Support others through change</li>
                </ul>

                <p style="margin: 0 0 6px 0;"><strong>Working collaboratively</strong></p>
                <ul style="margin: 0 0 0 18px; padding: 0;">
                    <li>Build relationships</li>
                    <li>Lead a collaborative team</li>
                    <li>Share good practice</li>
                </ul>
            </div>
        </td>
    </tr>
</table>';
        Framework::firstOrCreate([
            'name'        => 'Management and leadership framework',
            'slug'        => 'mlf',
            'description' => '<p>The framework consists of a code of practice, standards and competencies at all levels from entry level manager to executive, and a learning and development curriculum.</p>',
            'instructions' => '<h2>Welcome to your NHS Management and Leadership assessment</h2><p>In this assessment you will be presented with a series of questions.</p><p>To get the most value from this exercise answer these questions as accurately and honestly as you can.</p>',
            'report_intro' => '<p>Thank you for completing the NHS Management and Leadership Framework (MLF) assessment. This report provides you with a summary of your responses and highlights areas of strength as well as opportunities for development.</p><p>The MLF is designed to support the growth and development of leaders at all levels within the NHS. By reflecting on your current skills and behaviours, you can identify key areas to focus on for your personal and professional growth.</p>',
            'report_html' => $html,
        ]);
    }
}
