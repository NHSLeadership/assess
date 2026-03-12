<?php

use App\Livewire\AssessmentCompleted;
use Livewire\Livewire;
use Tests\TestCase;

uses(TestCase::class);

it('redirects when no assessmentId is provided', function () {
    Livewire::test(AssessmentCompleted::class)
        ->assertRedirect(route('frameworks'));
});
