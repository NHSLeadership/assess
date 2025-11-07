<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Stages extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Stage::factory()->createManyQuietly([
            ['name' => 'Fundamentals', 'description' => 'This stage outlines the essential competencies expected of every manager and leader who is accountable for operational delivery and the outcomes of others, regardless of their level of seniority or specific role. It also supports employees preparing for their first steps into a management or leadership position by setting out the minimum standard and expectations for those managing or leading in healthcare settings.', 'deleted_at' => null],
            ['name' => 'Stage 1: First Time Managers and Leaders', 'description' => 'This stage supports individuals in their first supervisory, leadership, or management role, typically with responsibility for just one team or line of accountability. The competencies help new and first-line managers and leaders understand what is expected of them as they begin to take responsibility for the work of others, in addition to their own.', 'deleted_at' => null],
            ['name' => 'Stage 2: Mid-Level Managers and Leaders', 'description' => 'This stage supports individuals who have been operating as accountable managers or leaders for a number of years. The competencies enable these experienced professionals to navigate the bridge between senior leadership and frontline delivery, aligning team performance with broader organisational goals.', 'deleted_at' => null],
            ['name' => 'Stage 3: Senior Managers and Leaders', 'description' => 'This stage supports individuals delivering operationally and accountable at a senior management level. The competencies enable these established leaders to translate organisational strategy into action, lead larger or more complex areas, and influence across functions or systems.', 'deleted_at' => null],
            ['name' => 'Stage 4: Executive Level - highest and most senior', 'description' => 'This stage supports individuals operating and accountable at the most senior level of their organisation, including Executive Directors, Non-Executive Directors and Board Members. The competencies enable these leaders to set or oversee strategic direction and vision, shape organisational culture, and collaborate with peers and stakeholders to deliver national and ministerial priorities.', 'deleted_at' => null],
        ]);
    }
}
