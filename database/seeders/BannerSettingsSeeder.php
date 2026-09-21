<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BannerSettingsSeeder extends Seeder
{
    public function run(): void
    {
     $body = <<<'HTML'
<p>
    The NHS Leadership Academy will soon move to the NHS College of Leadership and Management.
    <br>
    During this transition you can continue to use this service as normal.
    <br>
    For more information see <a class="nhsuk-notification-banner__link" href="#">our guidance</a>.
</p>
HTML;
        DB::table('settings')->insertOrIgnore([
            [
                'group'      => 'banner',
                'name'       => 'title',
                'payload'    => json_encode('Organisation change'),
                'locked'     => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'group'      => 'banner',
                'name'       => 'body',
                'payload'    => json_encode($body),
                'locked'     => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
