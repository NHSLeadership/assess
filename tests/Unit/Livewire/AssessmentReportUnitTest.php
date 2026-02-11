<?php

use App\Livewire\AssessmentReport;

it('returns null when frameworkId is empty', function () {
    $component = new AssessmentReport();
    $component->frameworkId = null;

    expect($component->framework())->toBeNull();
});
