<?php

namespace Database\Seeders;

use App\Models\Scale;
use Illuminate\Database\Seeder;

class ScaleOptionVariantSeeder extends Seeder
{
    public function run(): void
    {
        $scale = Scale::query()
            ->where('name', 'Capability likert 1-5')
            ->with('options')
            ->first();

        if (! $scale) {
            return;
        }

        $raterTextByValue = [
            1 => 'This person can’t do this without help',
            2 => 'This person can do parts of this but need guidance',
            3 => 'This person can do most of this with occasional help',
            4 => 'This person can do all of this independently',
            5 => 'This person does this effectively and can support others to do this',
        ];

        foreach ($scale->options as $option) {
            $text = $raterTextByValue[(int) $option->value] ?? null;

            if (! $text) {
                continue;
            }

            $option->variants()->updateOrCreate(
                ['audience' => 'rater'],
                ['text' => $text, 'priority' => 0]
            );
        }
    }
}
