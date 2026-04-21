<?php

use App\Livewire\Frameworks;
use App\Models\Assessment;
use App\Models\Framework;
use App\Services\Auth0UserService;
use App\Settings\Retention;
use Illuminate\Support\Carbon;
use Livewire\Livewire;
use function Pest\Laravel\assertDatabaseHas;

beforeEach(function () {
    Carbon::setTestNow();

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

it('extends expiring assessments and records a retention extend event', function () {
    $user = makeAuthUser([
        'user_id' => '1111111111',
    ]);
    $this->actingAs($user);

    $framework = Framework::factory()->create();

    $assessment = Assessment::factory()->create([
        'framework_id' => $framework->id,
        'user_id'      => '1111111111',
        'updated_at'   => now()->subYear()->addDay(), // inside warning window
    ]);

    $oldUpdatedAt = $assessment->updated_at;

    Livewire::test(Frameworks::class, [
        'frameworkId' => $framework->id,
    ])
        ->call('retainExpiringAssessments')
        ->assertHasNoErrors();

    $assessment->refresh();

    expect($assessment->updated_at->gt($oldUpdatedAt))->toBeTrue();

    assertDatabaseHas('retention_events', [
        'subject_type' => 'Assessment',
        'subject_id'   => $assessment->id,
        'action'       => 'extend',
    ]);
});
