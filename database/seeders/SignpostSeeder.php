<?php

namespace Database\Seeders;

use App\Models\Signpost;
use Illuminate\Database\Seeder;

class SignpostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $signposts = [
            ['node_id' => 2, 'framework_variant_option_id' => null, 'min_value' => 1, 'max_value' => 2, 'guidance' => '<p>Do a <a target="_blank" rel="noopener noreferrer nofollow" href="https://www.leadershipacademy.nhs.uk/bitesize/">bitesize course</a> on personal productivity and wellbeing</p>'],
            ['node_id' => 3, 'framework_variant_option_id' => null, 'min_value' => 1, 'max_value' => 2, 'guidance' => '<p>Do a <a target="_blank" rel="noopener noreferrer nofollow" href="https://www.leadershipacademy.nhs.uk/bitesize/">bitesize course</a> on prioritising for productivity</p>'],
        ];
        foreach ($signposts as $signpost) {
            Signpost::firstOrCreate(
                [
                    'node_id' => $signpost['node_id'],
                    'framework_variant_option_id' => $signpost['framework_variant_option_id'],
                    'min_value' => $signpost['min_value'],
                    'max_value' => $signpost['max_value'],
                    'guidance' => $signpost['guidance'],
                ],
            );
        }

    }
}
