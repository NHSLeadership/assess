<?php

use App\Livewire\Summary;

it('returns null when editAnswer receives a non-numeric nodeId', function () {
    $component = new Summary;

    $result = $component->editAnswer('abc');

    expect($result)->toBeNull();
});

