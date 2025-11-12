<?php

namespace Database\Seeders;

use App\Models\QuestionVariantMatch;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionVariantMatchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $matches = [
            ['question_variant_id' => 1, 'framework_variant_attribute_id' => 1, 'framework_variant_option_id' => 1],
            ['question_variant_id' => 2, 'framework_variant_attribute_id' => 1, 'framework_variant_option_id' => 2],
            ['question_variant_id' => 3, 'framework_variant_attribute_id' => 1, 'framework_variant_option_id' => 3],
            ['question_variant_id' => 4, 'framework_variant_attribute_id' => 1, 'framework_variant_option_id' => 4],
        ];
        foreach ($matches as $match) {
            QuestionVariantMatch::firstOrCreate(
                [
                    'question_variant_id' => $match['question_variant_id'],
                    'framework_variant_attribute_id' => $match['framework_variant_attribute_id'],
                    'framework_variant_option_id' => $match['framework_variant_option_id'],
                ],
            );
        }
    }
}
