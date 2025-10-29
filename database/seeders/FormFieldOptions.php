<?php

namespace Database\Seeders;

use App\Models\FormField;
use App\Models\FormFieldOption;
use Illuminate\Database\Seeder;

class FormFieldOptions extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (FormField::all() as $formField) {
            FormFieldOption::factory()->create(['form_field_id' => $formField->id, 'value' => 'Insufficient']);
            FormFieldOption::factory()->create(['form_field_id' => $formField->id, 'value' => 'Essential']);
            FormFieldOption::factory()->create(['form_field_id' => $formField->id, 'value' => 'Proficient']);
            FormFieldOption::factory()->create(['form_field_id' => $formField->id, 'value' => 'Strong']);
            FormFieldOption::factory()->create(['form_field_id' => $formField->id, 'value' => 'Exemplary']);
        }
    }
}
