<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (! app()->environment(['local', 'development', 'staging'])) {
            $this->command?->error('Seeding is disabled in production.');
            Log::warning('Database seeding attempted in production and was skipped.');
            return;
        }
        $this->call([
            FrameworkSeeder::class,
            NodeTypeSeeder::class,
            NodeSeeder::class,
            ScaleSeeder::class,
            QuestionSeeder::class,
            FrameworkVariantAttributeSeeder::class,
            FrameworkVariantOptionSeeder::class,
            QuestionVariantSeeder::class,
            QuestionVariantMatchSeeder::class,
        ]);
    }
}
