<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            FrameworkSeeder::class,
            NodeTypeSeeder::class,
            NodeSeeder::class,
            ScaleSeeder::class,
            QuestionSeeder::class,
            FrameworkVariantAttributeSeeder::class,
            FrameworkVariantOptionSeeder::class,
            QuestionVariantSeeder::class,
            QuestionVariantMatchSeeder::class,
            SignPostSeeder::class,
        ]);
    }
}
