<?php

use App\Livewire\Variants;
use App\Models\Assessment;
use App\Models\Framework;
use App\Models\Node;
use App\Models\NodeType;
use App\Models\Question;
use App\Models\Rater;
use App\Models\Response;
use App\Models\Scale;
use App\Models\ScaleOption;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('loads the framework when frameworkId is provided', function () {
    $user = makeAuthUser();
    Livewire::actingAs($user);

    $framework = Framework::factory()->create();

    Livewire::partialMock(\App\Livewire\FrameworkInstructions::class, function ($mock) {
        $mock->shouldReceive('redirectIfAssessmentNotPermitted')->andReturn(null);
        $mock->shouldReceive('redirectIfInvalidAssessment')->andReturn(null);
    });

    Livewire::test(\App\Livewire\FrameworkInstructions::class, [
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

    Livewire::partialMock(\App\Livewire\FrameworkInstructions::class, function ($mock) {
        $mock->shouldReceive('redirectIfAssessmentNotPermitted')->andReturn(null);
        $mock->shouldReceive('redirectIfInvalidAssessment')->andReturn(null);
    });

    Livewire::test(\App\Livewire\FrameworkInstructions::class, [
        'frameworkId' => $framework->id,
    ])
        ->assertViewIs('livewire.framework-instructions');
});
