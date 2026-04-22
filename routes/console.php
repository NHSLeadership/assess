<?php

use App\Jobs\DeleteExpiredAssessments;
use App\Jobs\SendRetentionWarnings;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function (): void {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::job(new SendRetentionWarnings)
    ->dailyAt('02:00');

Schedule::job(new DeleteExpiredAssessments)
    ->dailyAt('03:00');

Schedule::command('audits:prune-old')
    ->dailyAt('04:00')
    ->onOneServer()
    ->withoutOverlapping();
