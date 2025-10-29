<?php

namespace Database\Seeders;

use App\Models\FormArea;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            Stages::class,
            FormAreaSeeder::class,
            AssessmentSeeder::class,
            Forms::class,
            FormFields::class,
            FormFieldOptions::class,
        ]);
    }
}
