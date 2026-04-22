<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Models\Audit;

#[Signature('audits:prune-old')]
#[Description('Delete audit records more than a year old')]
class PruneOldAudits extends Command
{
    public function handle(): int
    {
        $cutoff = Carbon::now()->subYear();

        $deleted = Audit::query()
            ->where('created_at', '<', $cutoff)
            ->delete();

        $this->info("Deleted {$deleted} audit records older than one year.");

        return Command::SUCCESS;
    }
}
