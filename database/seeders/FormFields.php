<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FormFields extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\FormField::factory()->createManyQuietly([
            ['name' => 'leadership', 'form_id' => 1, 'group_id' => 1, 'element' => 'select', 'label' => 'Both leadership skills are essential', 'required' => 1],
            ['name' => 'management', 'form_id' => 1, 'group_id' => 1, 'element' => 'radio', 'label' => 'Great management skills are more important than leadership skills', 'required' => 1],
            ['name' => 'excellence', 'form_id' => 1, 'group_id' => 1, 'element' => 'checkbox', 'label' => 'Excellence is', 'required' => 1],
            ['name' => 'notes_grp_1', 'form_id' => 1, 'group_id' => 1, 'element' => 'textarea', 'label' => 'Group 1 notes', 'required' => 0],
            ['name' => 'planning', 'form_id' => 1, 'group_id' => 2, 'element' => 'select', 'label' => 'Planning skills are essential', 'required' => 1],
            ['name' => 'strategy', 'form_id' => 1, 'group_id' => 2, 'element' => 'radio', 'label' => 'Strategic approach is more important than than short term goals', 'required' => 1],
            ['name' => 'importance', 'form_id' => 1, 'group_id' => 2, 'element' => 'checkbox', 'label' => 'Choose important ones', 'hint' => 'Choose answers that are important to you', 'required' => 1],
            ['name' => 'notes_grp_2', 'form_id' => 1, 'group_id' => 2, 'element' => 'textarea', 'label' => 'Group 2 notes', 'required' => 0],
        ]);
    }
}
