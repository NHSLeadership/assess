<?php

use App\Livewire\AssessmentCompleted;
use App\Models\Assessment;
use App\Models\Framework;
use Livewire\Livewire;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('redirects when no assessment Id is provided', function () {
    Livewire::test(AssessmentCompleted::class)
        ->assertRedirect(route('frameworks'));
});

it('throws ModelNotFoundException when assessment is missing', function () {
    $user = makeAuthUser();
    $this->actingAs($user);

    $this->expectException(ModelNotFoundException::class);

    Livewire::test(\App\Livewire\AssessmentCompleted::class, [
        'assessmentId' => 123,
    ]);
});



