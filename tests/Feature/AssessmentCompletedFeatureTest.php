<?php

use App\Livewire\AssessmentCompleted;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('redirects when no assessment Id is provided', function () {
    Livewire::test(AssessmentCompleted::class)
        ->assertRedirect(route('frameworks'));
});

it('throws ModelNotFoundException when assessment is missing', function () {
    $user = makeAuthUser();
    $this->actingAs($user);

    $this->expectException(ModelNotFoundException::class);

    Livewire::test(AssessmentCompleted::class, [
        'assessmentId' => 123,
    ]);
});
