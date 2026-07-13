<?php

namespace App\Console\Commands;

use App\Models\Rater;
use App\Services\Auth0UserService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:backfill-raters-from-auth0 {--dry-run}')]
#[Description('Backfill missing rater names and emails from Auth0')]
class BackfillRatersFromAuth0 extends Command
{
    public function handle(Auth0UserService $auth0): int
    {
        $dryRun = (bool) $this->option('dry-run');

        $raters = Rater::query()
            ->where(function ($query) {
                $query->whereNull('name')
                    ->orWhereNull('email');
            })
            ->orderBy('id')
            ->get();

        if ($raters->isEmpty()) {
            $this->info('No raters require backfilling.');

            return self::SUCCESS;
        }

        $updated = 0;
        $skipped = 0;
        $failed = 0;

        $this->info(
            sprintf(
                'Found %d rater(s) requiring backfill.',
                $raters->count()
            )
        );

        foreach ($raters as $rater) {
            try {
                $profile = $auth0->getUserByUsername(
                    (string) $rater->subject_id
                );

                if (! $profile) {
                    $this->warn(
                        "No Auth0 user found for subject {$rater->subject_id}"
                    );

                    $skipped++;

                    continue;
                }

                $name = trim((string) ($profile['name'] ?? ''));
                $email = trim((string) ($profile['email'] ?? ''));

                if ($name === '' || $email === '') {
                    $this->warn(
                        "Incomplete Auth0 profile for subject {$rater->subject_id}"
                    );

                    $skipped++;

                    continue;
                }

                if ($dryRun) {

                    $this->line(
                        sprintf(
                            '[DRY RUN] subject_id=%s',
                            $rater->subject_id
                        )
                    );

                    $updated++;

                    continue;
                }

                $rater->update([
                    'name' => $name,
                    'email' => $email,
                ]);

                // Model casts encrypt name/email.
                // Model event automatically rebuilds email_hash.

                $updated++;

            } catch (\Throwable $e) {
                report($e);

                $this->error(
                    "Failed for subject {$rater->subject_id}: {$e->getMessage()}"
                );

                $failed++;
            }
        }

        $this->newLine();

        $this->table(
            ['Status', 'Count'],
            [
                [$dryRun ? 'Would update' : 'Updated', $updated],
                ['Skipped', $skipped],
                ['Failed', $failed],
            ]
        );

        return self::SUCCESS;
    }
}
