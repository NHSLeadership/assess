<?php

use App\Livewire\FrameworkInstructions;
use App\Models\Assessment;
use App\Models\Framework;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('loads the framework when frameworkId is provided', function () {
    $user = makeAuthUser();
    Livewire::actingAs($user);

    $framework = Framework::factory()->create();

    Livewire::partialMock(FrameworkInstructions::class, function ($mock) {
        $mock->shouldReceive('redirectIfAssessmentNotPermitted')->andReturn(null);
        $mock->shouldReceive('redirectIfInvalidAssessment')->andReturn(null);
    });

    Livewire::test(FrameworkInstructions::class, [
        'frameworkId' => $framework->id,
    ])
        ->tap(function ($test) use ($framework) {
            expect($test->instance()->framework()->id)->toBe($framework->id);
        });
});

it('renders the framework instructions view', function () {
    $user = makeAuthUser();
    Livewire::actingAs($user);

    $framework = Framework::factory()->create();

    Livewire::partialMock(FrameworkInstructions::class, function ($mock) {
        $mock->shouldReceive('redirectIfAssessmentNotPermitted')->andReturn(null);
        $mock->shouldReceive('redirectIfInvalidAssessment')->andReturn(null);
    });

    Livewire::test(FrameworkInstructions::class, [
        'frameworkId' => $framework->id,
    ])
        ->assertViewIs('livewire.framework-instructions');
});

it('dispatches page-title event when pageTitle is set', function () {
    $user = makeAuthUser();
    $this->actingAs($user);

    $framework = Framework::factory()->create();
    $assessment = Assessment::factory()
        ->for($framework)
        ->for($user)
        ->create();


    Livewire::test(FrameworkInstructions::class, [
        'frameworkId'  => $framework->id,
        'assessmentId' => $assessment->id,
    ])->assertDispatched(
    'page-title',
        title: config('app.page_title_prefix') . __('pages.instructions.title')
    );
});
