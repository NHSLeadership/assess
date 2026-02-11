<?php

use App\Exceptions\AssessmentFrameworkMismatchException;
use App\Exceptions\AssessmentNotFoundException;
use App\Exceptions\AssessmentNotSubmittedException;
use App\Livewire\AssessmentReport;
use App\Models\Assessment;
use App\Models\Framework;
use App\Exceptions\FrameworkNotFoundException;
use App\Models\Node;
use App\Services\AssessmentReportService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('throws FrameworkNotFoundException when framework does not exist', function () {
    $framework = Framework::factory()->create();

    $assessment = Assessment::factory()->create([
        'user_id' => 1,
        'framework_id' => $framework->id,
        'submitted_at' => now(),
    ]);

    $component = new AssessmentReport();

    $this->expectException(FrameworkNotFoundException::class);

    $component->mount(
        frameworkId: 999,              // nonexistent
        assessmentId: $assessment->id
    );
});

it('throws AssessmentNotFoundException when assessment does not exist', function () {
    $framework = Framework::factory()->create();

    $nonExistentAssessmentId = 999;

    $component = new AssessmentReport();

    $this->expectException(AssessmentNotFoundException::class);

    $component->mount(
        frameworkId: $framework->id,
        assessmentId: $nonExistentAssessmentId
    );
});

it('throws AssessmentFrameworkMismatchException when assessment belongs to a different framework', function () {
    $frameworkA = Framework::factory()->create();
    $frameworkB = Framework::factory()->create();

    $assessment = Assessment::factory()->create([
        'user_id' => 1,
        'framework_id' => $frameworkB->id, // belongs to B
        'submitted_at' => now(),
    ]);

    $component = new AssessmentReport();

    $this->expectException(AssessmentFrameworkMismatchException::class);

    $component->mount(
        frameworkId: $frameworkA->id,   // passing A
        assessmentId: $assessment->id   // but assessment belongs to B
    );
});

it('throws AssessmentNotSubmittedException when assessment is not submitted', function () {
    $framework = Framework::factory()->create();

    $assessment = Assessment::factory()->create([
        'user_id' => 1,
        'framework_id' => $framework->id,
        'submitted_at' => null, // key condition
    ]);

    $component = new AssessmentReport();

    $this->expectException(AssessmentNotSubmittedException::class);

    $component->mount(
        frameworkId: $framework->id,
        assessmentId: $assessment->id
    );
});

it('populates report data when framework and assessment are valid and submitted', function () {
    $framework = Framework::factory()->create();

    $assessment = Assessment::factory()->create([
        'user_id' => 1,
        'framework_id' => $framework->id,
        'submitted_at' => now(),
    ]);

    // Create a valid node type to satisfy FK
    $nodeType = \App\Models\NodeType::factory()->create();

    // Create a node that belongs to the framework and has a valid node_type_id
    $node = \App\Models\Node::factory()->create([
        'framework_id' => $framework->id,
        'node_type_id' => $nodeType->id,
    ]);

    // Mock the service that mount() instantiates
    $service = Mockery::mock('overload:' . AssessmentReportService::class);
    $service->shouldReceive('barCharts')->andReturn(['bar']);
    $service->shouldReceive('barChartsCompetency')->andReturn(['competency']);
    $service->shouldReceive('radarChart')->andReturn([
        'data' => ['radar-data'],
        'options' => ['radar-options'],
    ]);
    $service->shouldReceive('variantAttributeLabel')->andReturn('Variant Label');
    $service->shouldReceive('nodes')->andReturn(collect([$node]));
    $service->shouldReceive('signpostsForNode')->with($node)->andReturn(['signpost']);

    $component = new AssessmentReport();
    $component->mount($framework->id, $assessment->id);

    expect($component->barCharts)->toBe(['bar'])
        ->and($component->barChartsCompetency)->toBe(['competency'])
        ->and($component->radarData)->toBe(['radar-data'])
        ->and($component->radarOptions)->toBe(['radar-options'])
        ->and($component->variantAttributeLabel)->toBe('Variant Label')
        ->and($component->signposts)->toBe([
            $node->id => ['signpost'],
        ]);
});

it('returns nodes for the framework in the correct order', function () {
    $frameworkA = Framework::factory()->create();
    $frameworkB = Framework::factory()->create();

    $nodeType = \App\Models\NodeType::factory()->create();

    // Nodes for framework A (should be returned)
    $node1 = \App\Models\Node::factory()->create([
        'framework_id' => $frameworkA->id,
        'node_type_id' => $nodeType->id,
        'order' => 2,
    ]);

    $node2 = \App\Models\Node::factory()->create([
        'framework_id' => $frameworkA->id,
        'node_type_id' => $nodeType->id,
        'order' => 1,
    ]);

    // Node for framework B (should NOT be returned)
    \App\Models\Node::factory()->create([
        'framework_id' => $frameworkB->id,
        'node_type_id' => $nodeType->id,
        'order' => 1,
    ]);

    $component = new AssessmentReport();
    $component->frameworkId = $frameworkA->id;

    $nodes = $component->nodes();

    expect($nodes)->toHaveCount(2)
        ->and($nodes->pluck('id')->all())->toBe([
            $node2->id, // order 1
            $node1->id, // order 2
        ]);
});

it('returns responses for the assessment', function () {
    $framework = Framework::factory()->create();

    $assessment = Assessment::factory()->create([
        'user_id' => 1,
        'framework_id' => $framework->id,
        'submitted_at' => now(),
    ]);

    $nodeType = \App\Models\NodeType::factory()->create();

    $node = \App\Models\Node::factory()->create([
        'framework_id' => $framework->id,
        'node_type_id' => $nodeType->id,
    ]);

    $questionA = \App\Models\Question::factory()->create([
        'node_id' => $node->id,
    ]);

    $questionB = \App\Models\Question::factory()->create([
        'node_id' => $node->id,
    ]);

    $scale = \App\Models\Scale::factory()->create();
    $scaleOption = \App\Models\ScaleOption::factory()->create([
        'scale_id' => $scale->id,
    ]);

    $rater = \App\Models\Rater::factory()->create([
        'user_id' => 1,
    ]);

    $responseA = \App\Models\Response::factory()->create([
        'assessment_id' => $assessment->id,
        'rater_id' => $rater->id,
        'question_id' => $questionA->id,
        'scale_option_id' => $scaleOption->id,
    ]);

    $responseB = \App\Models\Response::factory()->create([
        'assessment_id' => $assessment->id,
        'rater_id' => $rater->id,
        'question_id' => $questionB->id,
        'scale_option_id' => $scaleOption->id,
    ]);

    // Valid “other assessment” to avoid FK errors
    $otherAssessment = Assessment::factory()->create([
        'user_id' => 1,
        'framework_id' => $framework->id,
        'submitted_at' => now(),
    ]);

    \App\Models\Response::factory()->create([
        'assessment_id' => $otherAssessment->id,
        'rater_id' => $rater->id,
        'question_id' => $questionA->id,
        'scale_option_id' => $scaleOption->id,
    ]);

    $component = new AssessmentReport();
    $component->assessmentId = $assessment->id;

    $responses = $component->responses();

    expect($responses)->toHaveCount(2)
        ->and($responses->pluck('id')->sort()->values()->all())->toBe(
            collect([$responseA->id, $responseB->id])->sort()->values()->all()
        );

});

it('returns the rater for the assessment', function () {
    $framework = Framework::factory()->create();

    $user = makeAuthUser();

    // Log in the user so auth()->user() is not null
    $this->actingAs($user);

    // Assessment owned by the "user" as interpreted by the component
    $assessment = Assessment::factory()->create([
        'user_id' => $user->user_id, // must match preferred_username
        'framework_id' => $framework->id,
        'submitted_at' => now(),
    ]);

    // Rater linked to the same "user"
    $rater = \App\Models\Rater::factory()->create([
        'user_id' => $user->user_id,
    ]);

    // Pivot: rater ↔ assessment
    $rater->assessments()->attach($assessment->id);

    $component = new AssessmentReport();
    $component->assessmentId = $assessment->id;

    $result = $component->rater();

    expect($result?->id)->toBe($rater->id);
});


