<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RetentionSettingsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('settings')->insertOrIgnore([
            [
                'group'      => 'retention',
                'name'       => 'retention_years',
                'payload'    => json_encode(6),
                'locked'     => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'group'      => 'retention',
                'name'       => 'expiry_warning_days',
                'payload'    => json_encode(30),
                'locked'     => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'group'      => 'retention',
                'name'       => 'min_days_after_warning',
                'payload'    => json_encode(7),
                'locked'     => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
