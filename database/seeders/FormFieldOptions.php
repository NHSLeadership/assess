<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FormFieldOptions extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\FormFieldOption::factory()->createManyQuietly([
            ['form_field_id' => 1, 'value' => 'Strongly disagree'],
            ['form_field_id' => 1, 'value' => 'Disagree'],
            ['form_field_id' => 1, 'value' => 'Neutral'],
            ['form_field_id' => 1, 'value' => 'Agree'],
            ['form_field_id' => 1, 'value' => 'Strongly agree'],

            ['form_field_id' => 2, 'value' => 'Yes'],
            ['form_field_id' => 2, 'value' => 'No'],

            ['form_field_id' => 3, 'value' => 'Average leadership and great management'],
            ['form_field_id' => 3, 'value' => 'Great leadership and average management'],
            ['form_field_id' => 3, 'value' => 'Balanced leadership and management'],
            ['form_field_id' => 3, 'value' => 'Excellent leadership and management'],
            ['form_field_id' => 3, 'value' => 'Leadership and management that produces results'],

            ['form_field_id' => 5, 'value' => 'Strongly disagree'],
            ['form_field_id' => 5, 'value' => 'Disagree'],
            ['form_field_id' => 5, 'value' => 'Neutral'],
            ['form_field_id' => 5, 'value' => 'Agree'],
            ['form_field_id' => 5, 'value' => 'Strongly agree'],

            ['form_field_id' => 6, 'value' => 'Yes'],
            ['form_field_id' => 6, 'value' => 'No'],

            ['form_field_id' => 7, 'value' => 'Vision is important but it does not need to be communicated'],
            ['form_field_id' => 7, 'value' => 'Developing a vision is leader priority'],
            ['form_field_id' => 7, 'value' => 'Applying your vision to decision-making and prioritisation is vital'],
            ['form_field_id' => 7, 'value' => 'Ensuring continuous improvement is a part of your vision'],
            ['form_field_id' => 7, 'value' => 'Compelling vision inspires your team'],
        ]);
    }
}
