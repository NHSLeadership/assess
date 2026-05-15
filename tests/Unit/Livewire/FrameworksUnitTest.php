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

test('getAssessmentStatusTag returns "Not started" for assessments with no responses', function () {
    $component = new FrameworksFake;

    $assessment = new Assessment();
    $assessment->submitted_at = null;
    $assessment->created_at = Carbon::now();  // Add timestamp to avoid expiresAt() error
    $assessment->setRelation('responses', collect([]));

    $result = $component->getAssessmentStatusTag($assessment);

    expect($result)
        ->toHaveKeys(['class', 'text', 'subtitle'])
        ->and($result['class'])->toBe('nhsuk-tag--red')
        ->and($result['text'])->toBe(__('Not started'))
        ->and($result['subtitle'])->toBeNull();
});

test('getAssessmentStatusTag returns "Started" for assessments with partial responses', function () {
    $component = new FrameworksFake;

    $assessment = new Assessment();
    $assessment->submitted_at = null;
    $assessment->created_at = Carbon::now();  // Add timestamp to avoid expiresAt() error

    // Mock some responses
    $response1 = (object) ['id' => 1];
    $response2 = (object) ['id' => 2];
    $assessment->setRelation('responses', collect([$response1, $response2]));

    // For "Started" state, we need the response count to NOT equal required questions count
    // This is a partial response state (not N+1 query issue, just incomplete)
    $result = $component->getAssessmentStatusTag($assessment);

    expect($result)
        ->toHaveKeys(['class', 'text', 'subtitle'])
        ->and($result['class'])->toBe('nhsuk-tag--blue')
        ->and($result['text'])->toBe(__('Started'))
        ->and($result['subtitle'])->toBeNull();
});

test('getAssessmentStatusTag returns "Completed" for submitted assessments', function () {
    $component = new FrameworksFake;

    $assessment = new Assessment();
    $assessment->submitted_at = Carbon::now()->subDay();
    $assessment->created_at = Carbon::now()->subDay();
    $assessment->setRelation('responses', collect([]));

    $result = $component->getAssessmentStatusTag($assessment);

    expect($result)
        ->toHaveKeys(['class', 'text', 'subtitle'])
        ->and($result['class'])->toBe('nhsuk-tag--green')
        ->and($result['text'])->toBe(__('Completed'))
        ->and($result['subtitle'])->toBeNull();
});

test('hasVariantAttributes returns false when framework is null', function () {
    $component = new FrameworksFake;
    $component->frameworkId = null;

    $result = $component->hasVariantAttributes();

    expect($result)->toBeFalse();
});