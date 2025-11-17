<?php

namespace Database\Seeders;

use App\Models\FormField;
use App\Models\FormFieldOption;
use Illuminate\Database\Seeder;

class FormFieldOptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (FormField::all() as $formField) {
            FormFieldOption::factory()->create(['form_field_id' => $formField->id, 'name' => 'Insufficient', 'value' => 1]);
            FormFieldOption::factory()->create(['form_field_id' => $formField->id, 'name' => 'Essential', 'value' => 2]);
            FormFieldOption::factory()->create(['form_field_id' => $formField->id, 'name' => 'Proficient', 'value' => 3]);
            FormFieldOption::factory()->create(['form_field_id' => $formField->id, 'name' => 'Strong', 'value' => 4]);
            FormFieldOption::factory()->create(['form_field_id' => $formField->id, 'name' => 'Exemplary', 'value' => 5]);
        }
    }
}
