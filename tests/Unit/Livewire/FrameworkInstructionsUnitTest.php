<?php

use App\Livewire\FrameworkInstructions;
use App\Models\Framework;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns null when frameworkId is null', function () {
    $component = new FrameworkInstructions();
    $component->frameworkId = null;

    expect($component->framework())->toBeNull();
});
