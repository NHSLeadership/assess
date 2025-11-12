<?php

namespace Database\Seeders;

use App\Models\QuestionVariant;
use Illuminate\Database\Seeder;

class QuestionVariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $variants = [
            ['question_id' => 1, 'text' => 'I prioritise tasks effectively and make sure my team and I have the resources we need to deliver efficiently and on time.', 'rater_type' => null, 'priority' => 0],
            ['question_id' => 1, 'text' => 'I deliver efficiently and on time across teams by planning ahead, managing risks, adjusting priorities, delegating, and managing upwards when necessary.', 'rater_type' => null, 'priority' => 0],
            ['question_id' => 1, 'text' => 'I manage and align complex and evolving priorities across my areas of responsibility to ensure timely and cost-effective delivery.', 'rater_type' => null, 'priority' => 0],
            ['question_id' => 1, 'text' => 'I champion a strategic approach to prioritising work and resources to drive sustainable and efficient improvement.', 'rater_type' => null, 'priority' => 0],
        ];
        foreach ($variants as $variant) {
            QuestionVariant::firstOrCreate(
                [
                    'question_id' => $variant['question_id'],
                    'text' => $variant['text'],
                    'rater_type' => $variant['rater_type'],
                    'priority' => $variant['priority'],
                ],
            );
        }
    }
}
