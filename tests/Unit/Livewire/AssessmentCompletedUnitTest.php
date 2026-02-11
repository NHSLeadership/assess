<?php

uses(Tests\TestCase::class);

use App\Livewire\AssessmentCompleted;
use Livewire\Livewire;

it('redirects when no assessmentId is provided', function () {
    Livewire::test(AssessmentCompleted::class)
        ->assertRedirect(route('frameworks'));
});
