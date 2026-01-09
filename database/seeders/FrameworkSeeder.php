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
        Framework::firstOrCreate([
            'name'        => 'Management and leadership framework',
            'slug'        => 'mlf',
            'description' => '<p>The framework consists of a code of practice, standards and competencies at all levels from entry level manager to executive, and a learning and development curriculum.</p>',
            'instructions' => '<h2>Welcome to your NHS Management and Leadership assessment</h2><p>In this assessment you will be presented with a series of questions.</p><p>To get the most value from this exercise answer these questions as accurately and honestly as you can.</p>',
            'report_intro' => '<p>Thank you for completing the NHS Management and Leadership Framework (MLF) assessment. This report provides you with a summary of your responses and highlights areas of strength as well as opportunities for development.</p><p>The MLF is designed to support the growth and development of leaders at all levels within the NHS. By reflecting on your current skills and behaviours, you can identify key areas to focus on for your personal and professional growth.</p>',
        ]);
    }
}
