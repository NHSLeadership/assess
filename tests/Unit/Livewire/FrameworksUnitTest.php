<?php

use App\Models\Assessment;
use Carbon\Carbon;
use Tests\Support\FrameworksFake;

test('displayAssessmentDate uses submitted_at when present', function () {
    $component = new FrameworksFake;

    $assessment = new Assessment();

    $assessment->submitted_at = Carbon::create(2024, 1, 1);
    $assessment->created_at = null;
    $assessment->updated_at = null;

    $assessment->setRelation('responses', collect([]));

    $result = $component->displayAssessmentDate($assessment);

    expect($result)->toBe('1 January 2024');
});

test('displayAssessmentDate uses latest response date when not submitted', function () {
    $component = new FrameworksFake;

    $assessment = new Assessment();
    $assessment->created_at = null;
    $assessment->submitted_at = null;
    $assessment->updated_at  = null;

    $response1 = (object) [
        'updated_at' => Carbon::create(2024, 1, 1),
    ];

    $response2 = (object) [
        'updated_at' => Carbon::create(2024, 2, 1),
    ];

    $assessment->setRelation('responses', collect([$response1, $response2]));

    $result = $component->displayAssessmentDate($assessment);

    expect($result)->toBe('1 February 2024');
});

test('displayProgress returns Not available when assessment is null', function () {
    $component = new FrameworksFake;

    expect($component->displayProgress(null))->toBe('Not available');
});
