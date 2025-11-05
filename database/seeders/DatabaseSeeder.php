<?php

namespace Database\Seeders;

use App\Models\Area;
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
            AreaSeeder::class,
            AssessmentSeeder::class,
            Forms::class,
            FormFields::class,
            FormFieldOptions::class,
        ]);
    }
}
