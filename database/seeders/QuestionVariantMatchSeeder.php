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

            ['question_variant_id' => 5, 'framework_variant_attribute_id' => 1, 'framework_variant_option_id' => 1],
            ['question_variant_id' => 6, 'framework_variant_attribute_id' => 1, 'framework_variant_option_id' => 2],
            ['question_variant_id' => 7, 'framework_variant_attribute_id' => 1, 'framework_variant_option_id' => 3],
            ['question_variant_id' => 8, 'framework_variant_attribute_id' => 1, 'framework_variant_option_id' => 4],

            ['question_variant_id' => 9, 'framework_variant_attribute_id' => 1, 'framework_variant_option_id' => 1],
            ['question_variant_id' => 10, 'framework_variant_attribute_id' => 1, 'framework_variant_option_id' => 2],
            ['question_variant_id' => 11, 'framework_variant_attribute_id' => 1, 'framework_variant_option_id' => 3],
            ['question_variant_id' => 12, 'framework_variant_attribute_id' => 1, 'framework_variant_option_id' => 4],

            ['question_variant_id' => 13, 'framework_variant_attribute_id' => 1, 'framework_variant_option_id' => 1],
            ['question_variant_id' => 14, 'framework_variant_attribute_id' => 1, 'framework_variant_option_id' => 2],
            ['question_variant_id' => 15, 'framework_variant_attribute_id' => 1, 'framework_variant_option_id' => 3],
            ['question_variant_id' => 16, 'framework_variant_attribute_id' => 1, 'framework_variant_option_id' => 4],

            ['question_variant_id' => 17, 'framework_variant_attribute_id' => 1, 'framework_variant_option_id' => 1],
            ['question_variant_id' => 18, 'framework_variant_attribute_id' => 1, 'framework_variant_option_id' => 2],
            ['question_variant_id' => 19, 'framework_variant_attribute_id' => 1, 'framework_variant_option_id' => 3],
            ['question_variant_id' => 20, 'framework_variant_attribute_id' => 1, 'framework_variant_option_id' => 4],

            ['question_variant_id' => 21, 'framework_variant_attribute_id' => 1, 'framework_variant_option_id' => 1],
            ['question_variant_id' => 22, 'framework_variant_attribute_id' => 1, 'framework_variant_option_id' => 2],
            ['question_variant_id' => 23, 'framework_variant_attribute_id' => 1, 'framework_variant_option_id' => 3],
            ['question_variant_id' => 24, 'framework_variant_attribute_id' => 1, 'framework_variant_option_id' => 4],

            ['question_variant_id' => 25, 'framework_variant_attribute_id' => 1, 'framework_variant_option_id' => 1],
            ['question_variant_id' => 26, 'framework_variant_attribute_id' => 1, 'framework_variant_option_id' => 2],
            ['question_variant_id' => 27, 'framework_variant_attribute_id' => 1, 'framework_variant_option_id' => 3],
            ['question_variant_id' => 28, 'framework_variant_attribute_id' => 1, 'framework_variant_option_id' => 4],

            ['question_variant_id' => 29, 'framework_variant_attribute_id' => 1, 'framework_variant_option_id' => 1],
            ['question_variant_id' => 30, 'framework_variant_attribute_id' => 1, 'framework_variant_option_id' => 2],
            ['question_variant_id' => 31, 'framework_variant_attribute_id' => 1, 'framework_variant_option_id' => 3],
            ['question_variant_id' => 32, 'framework_variant_attribute_id' => 1, 'framework_variant_option_id' => 4],

            ['question_variant_id' => 33, 'framework_variant_attribute_id' => 1, 'framework_variant_option_id' => 1],
            ['question_variant_id' => 34, 'framework_variant_attribute_id' => 1, 'framework_variant_option_id' => 2],
            ['question_variant_id' => 35, 'framework_variant_attribute_id' => 1, 'framework_variant_option_id' => 3],
            ['question_variant_id' => 36, 'framework_variant_attribute_id' => 1, 'framework_variant_option_id' => 4],

            ['question_variant_id' => 37, 'framework_variant_attribute_id' => 1, 'framework_variant_option_id' => 1],
            ['question_variant_id' => 38, 'framework_variant_attribute_id' => 1, 'framework_variant_option_id' => 2],
            ['question_variant_id' => 39, 'framework_variant_attribute_id' => 1, 'framework_variant_option_id' => 3],
            ['question_variant_id' => 40, 'framework_variant_attribute_id' => 1, 'framework_variant_option_id' => 4],

            ['question_variant_id' => 41, 'framework_variant_attribute_id' => 1, 'framework_variant_option_id' => 1],
            ['question_variant_id' => 42, 'framework_variant_attribute_id' => 1, 'framework_variant_option_id' => 2],
            ['question_variant_id' => 43, 'framework_variant_attribute_id' => 1, 'framework_variant_option_id' => 3],
            ['question_variant_id' => 44, 'framework_variant_attribute_id' => 1, 'framework_variant_option_id' => 4],

            ['question_variant_id' => 45, 'framework_variant_attribute_id' => 1, 'framework_variant_option_id' => 1],
            ['question_variant_id' => 46, 'framework_variant_attribute_id' => 1, 'framework_variant_option_id' => 2],
            ['question_variant_id' => 47, 'framework_variant_attribute_id' => 1, 'framework_variant_option_id' => 3],
            ['question_variant_id' => 48, 'framework_variant_attribute_id' => 1, 'framework_variant_option_id' => 4],
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
