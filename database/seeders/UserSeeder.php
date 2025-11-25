<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::factory()->createOneQuietly([
            'name' => 'nhsuser',
            'email' => 'nhsuser@nhs.net',
            'password' => str()->password(),
        ]);
    }
}
