<?php

use App\Jobs\DeleteExpiredAssessments;
use App\Jobs\SendRetentionWarnings;
use App\Models\Assessment;
use App\Services\Auth0UserService;
use App\Settings\Retention;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use function Pest\Laravel\assertDatabaseHas;

beforeEach(function () {
    Carbon::setTestNow(now());

    $settings = app(Retention::class);

    $settings->retention_years = 1;
    $settings->expiry_warning_days = 30;
    $settings->min_days_after_warning = 7;

    $settings->save();

    $auth0 = Mockery::mock(Auth0UserService::class);
    $auth0->shouldReceive('getUserByUsername')
        ->andReturn([
            'email'    => 'test@example.com',
            'username' => '1111111111',
            'user_id'  => 'auth0|test-user',
        ]);

    app()->instance(Auth0UserService::class, $auth0);

});

it('records a retention warning event when an assessment enters the warning window', function () {
    Mail::fake();

    $assessment = Assessment::factory()->create([
        'user_id' => '1111111111',
        'updated_at' => now()
            ->subYears()
            ->addDays(1), // 29 days to expiry
    ]);

    SendRetentionWarnings::dispatchSync();

    assertDatabaseHas('retention_events', [
        'subject_type' => 'Assessment',
        'subject_id'   => $assessment->id,
        'action'       => 'warn',
    ]);
});

it('does not record a duplicate warning for the same assessment in the same retention cycle', function () {
    Mail::fake();

    $assessment = Assessment::factory()->create([
        'user_id' => '1111111111',
        'updated_at' => now()->subYear()->addDay(),
    ]);

    // First run – records warning
    SendRetentionWarnings::dispatchSync();

    // Second run – should do nothing
    SendRetentionWarnings::dispatchSync();

    expect(
        \DB::table('retention_events')
            ->where('subject_type', 'Assessment')
            ->where('subject_id', $assessment->id)
            ->where('action', 'warn')
            ->count()
    )->toBe(1);
});

it('does not record a warning when assessment is outside the warning window', function () {
    Mail::fake();

    $assessment = Assessment::factory()->create([
        'user_id' => '1111111111',
        // Far from expiry (e.g. just updated)
        'updated_at' => now(),
    ]);

    SendRetentionWarnings::dispatchSync();

    expect(
        \DB::table('retention_events')->count()
    )->toBe(0);
});

it('records a new warning when retention years are extended after a prior warning', function () {
    Mail::fake();

    $assessment = Assessment::factory()->create([
        'user_id' => '1111111111',
        'updated_at' => now()->subYear()->addDay(),
    ]);

    // Initial warning
    SendRetentionWarnings::dispatchSync();

    // Extend retention policy
    $settings = app(Retention::class);
    $settings->retention_years = 2;
    $settings->save();

    // Move time forward into new warning window
    Carbon::setTestNow(now()->addYear()->subDays(29));

    SendRetentionWarnings::dispatchSync();

    expect(
        \DB::table('retention_events')
            ->where('subject_id', $assessment->id)
            ->where('action', 'warn')
            ->count()
    )->toBe(2);
});

it('does not delete an assessment if no prior retention warning exists', function () {
    $assessment = Assessment::factory()->create([
        'user_id' => '1111111111',
        'updated_at' => now()->subYears(2),
    ]);

    DeleteExpiredAssessments::dispatchSync();

    // Assessment should still exist
    expect(
        Assessment::query()->whereKey($assessment->id)->exists()
    )->toBeTrue();
});

it('deletes an assessment only after a warning and the minimum delay has passed', function () {
    Mail::fake();

    $assessment = Assessment::factory()->create([
        'user_id' => '1111111111',
        'updated_at' => now()->subYear()->addDay(),
    ]);

    // Issue warning
    SendRetentionWarnings::dispatchSync();

    // Move time past min_days_after_warning
    Carbon::setTestNow(now()->addDays(8));

    DeleteExpiredAssessments::dispatchSync();

    expect(
        Assessment::query()->whereKey($assessment->id)->exists()
    )->toBeFalse();

    assertDatabaseHas('retention_events', [
        'subject_type' => 'Assessment',
        'subject_id'   => $assessment->id,
        'action'       => 'delete',
    ]);
});

it('records deletion only once even if delete job runs multiple times', function () {
    Mail::fake();

    $assessment = Assessment::factory()->create([
        'user_id' => '1111111111',
        'updated_at' => now()->subYear()->addDay(),
    ]);

    SendRetentionWarnings::dispatchSync();

    Carbon::setTestNow(now()->addDays(8));

    DeleteExpiredAssessments::dispatchSync();
    DeleteExpiredAssessments::dispatchSync();

    expect(
        \DB::table('retention_events')
            ->where('subject_id', $assessment->id)
            ->where('action', 'delete')
            ->count()
    )->toBe(1);
});
