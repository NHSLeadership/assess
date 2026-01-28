<?php

use App\Livewire\Summary;
use App\Models\Assessment;
use App\Models\AssessmentRater;
use App\Models\Framework;
use App\Models\NodeType;
use App\Models\Question;
use App\Models\Rater;
use App\Models\Response;
use App\Models\Scale;
use App\Models\ScaleOption;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Node;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('returns null when editAnswer receives a non-numeric nodeId', function () {

    $user = makeAuthUser();
    $this->actingAs($user);
    $framework = Framework::factory()->create();
    $assessment = Assessment::factory()
        ->for($framework)
        ->for($user)
        ->create();

    Livewire::test(Summary::class, [
        'assessmentId' => $assessment->id,
    ])
        ->call('editAnswer', 'abc')
        ->assertReturned(null);
});

it('redirects to questions when editAnswer receives a numeric nodeId', function () {

    // User is NOT persisted — using make()
    $user = makeAuthUser();

    // Authenticate the made user
    $this->be($user);

    $framework = Framework::factory()->create();
    $assessment = Assessment::factory()
        ->for($framework)
        ->for($user)
        ->create();

    Livewire::test(Summary::class, [
        'assessmentId' => $assessment->id,
    ])
        ->call('editAnswer', 5)
        ->assertRedirect(route('questions', [
            'assessmentId' => $assessment->id,
            'nodeId' => 5,
            'edit' => 'edit',
        ]));
});

it('redirects to questions when a resume node exists', function () {

    // User is NOT persisted — using make()
    $user = makeAuthUser();

    // Authenticate the made user
    $this->be($user);

    $framework = Framework::factory()->create();
    $assessment = Assessment::factory()
        ->for($framework)
        ->for($user)
        ->create();

    // Create a node + question graph using the shared helper
    $setup = createNodeWithQuestions(1, 'scale');
    $framework = $setup['framework'];
    $node = $setup['node'];
    $question = $setup['questions']->first();

    // Create a response that marks this node as unanswered
    // so getAssessmentResumeNode() returns this node
    // (no response = unanswered)
    // No need to create a response record

    Livewire::test(Summary::class, [
        'assessmentId' => $assessment->id,
        'frameworkId' => $framework->id,
    ])
        ->call('continueAssessment')
        ->assertRedirect(route('questions', [
            'assessmentId' => $assessment->id,
            'nodeId' => $node->id,
        ]));
});

it('redirects to frameworks when no resume node exists', function () {

    // User is NOT persisted — using make()
    $user = makeAuthUser();

    // Authenticate the made user
    $this->be($user);

    $rater = Rater::factory()->create([
        'user_id' => $user->user_id,
    ]);

    // Framework + Assessment
    $framework = Framework::factory()->create();
    $assessment = Assessment::factory()->create([
        'framework_id' => $framework->id,
        'user_id' => $rater->user_id,
    ]);

    // Node + Question (use helper to create a node under the same framework)
    $setup = createNodeWithQuestions(1, 'scale', ['framework' => $framework]);
    $node = $setup['node'];
    $question = $setup['questions']->first();

    // Scale + Option
    $scaleSetup = createScaleWithOption();
    $scale = $scaleSetup['scale'];
    $scaleOption = $scaleSetup['scaleOption'];

    // Response
    createResponseForAssessment($assessment, $rater, $question, $scaleOption);

    Livewire::test(Summary::class, [
        'assessmentId' => $assessment->id,
        'frameworkId' => $framework->id,
    ])
        ->call('continueAssessment')
        ->assertRedirect(route('frameworks'));
});

it('surfaces an error and scrolls to top when assessment is already submitted', function () {

    // User is NOT persisted — using make()
    $user = makeAuthUser();

    // Authenticate the made user
    $this->be($user);

    $framework = Framework::factory()->create();
    $assessment = Assessment::factory()->create([
        'framework_id' => $framework->id,
        'user_id' => $user->user_id,
        'submitted_at' => now(),
    ]);

    Livewire::test(Summary::class, [
        'assessmentId' => $assessment->id,
    ])
        ->call('confirmSubmit')
        ->assertReturned(null)
        ->assertDispatched('scroll-to-top'); // always required
});

it('submits the assessment and redirects to the completed page', function () {

    // User is NOT persisted — using make()
    $user = makeAuthUser();

    $this->be($user);

    $framework = Framework::factory()->create();
    $assessment = Assessment::factory()->create([
        'framework_id' => $framework->id,
        'user_id' => $user->user_id,
        'submitted_at' => null,
    ]);

    Livewire::test(Summary::class, [
        'assessmentId' => $assessment->id,
    ])
        ->call('confirmSubmit')
        ->assertRedirect(route('assessment-completed', [
            'assessmentId' => $assessment->id,
        ]));

    // Refresh the model to check DB changes
    $assessment->refresh();

    expect($assessment->submitted_at)->not->toBeNull();

});

it('returns the correct rater for the assessment via pivot table', function () {

    // User is NOT persisted — using make()
    $user = makeAuthUser();

    $this->be($user);

    // Create a rater
    $rater = Rater::factory()->create([
        'user_id' => $user->user_id,
    ]);

    // Create a framework + assessment
    $framework = Framework::factory()->create();
    $assessment = Assessment::factory()->create([
        'framework_id' => $framework->id,
        'user_id' => $rater->user_id,
    ]);

    // Attach rater to assessment via pivot
    AssessmentRater::factory()->create([
        'assessment_id' => $assessment->id,
        'rater_id' => $rater->id,
    ]);

    Livewire::test(Summary::class, [
        'assessmentId' => $assessment->id,
    ])
        ->assertSet('rater.id', $rater->id);
});

it('returns nodes ordered by order for the framework', function () {

    // User is NOT persisted — using make()
    $user = makeAuthUser();

    $this->be($user);

    // Framework + Assessment
    $framework = Framework::factory()->create();
    $assessment = Assessment::factory()->create([
        'framework_id' => $framework->id,
        'user_id' => $user->user_id,
    ]);

    $nodeType = NodeType::factory()->create();
    // Create nodes out of order
    $nodeB = Node::factory()->create([
        'framework_id' => $framework->id,
        'node_type_id' => $nodeType->id,
        'order' => 2,
    ]);

    $nodeA = Node::factory()->create([
        'framework_id' => $framework->id,
        'node_type_id' => $nodeType->id,
        'order' => 1,
    ]);

    // Create a node from another framework (should NOT appear)
    Node::factory()->create([
        'framework_id' => Framework::factory()->create()->id,
        'node_type_id' => $nodeType->id,
        'order' => 1,
    ]);

    Livewire::test(Summary::class, [
        'assessmentId' => $assessment->id,
        'frameworkId' => $framework->id,
    ])
        ->assertSet('nodes.0.id', $nodeA->id)
        ->assertSet('nodes.1.id', $nodeB->id)
        ->assertCount('nodes', 2);
});

it('returns the correct framework for the assessment', function () {

    // User is NOT persisted — using make()
    $user = makeAuthUser();

    $this->be($user);

    // Framework + Assessment
    $framework  = Framework::factory()->create();
    $assessment = Assessment::factory()->create([
        'framework_id' => $framework->id,
        'user_id'      => $user->user_id,
    ]);

    Livewire::test(Summary::class, [
        'assessmentId' => $assessment->id,
        'frameworkId'  => $framework->id,
    ])
        ->assertSet('framework.id', $framework->id);
});

it('returns null when no frameworkId is provided', function () {

    $user = makeAuthUser();

    $this->be($user);
    $framework  = Framework::factory()->create();
    $assessment = Assessment::factory()->create([
        'framework_id' => $framework->id,
        'user_id'      => $user->user_id,
    ]);

    Livewire::test(Summary::class, [
        'assessmentId' => $assessment->id,
        'frameworkId'  => null,
    ])
        ->assertSet('framework', null);
});

it('returns all responses for the assessment', function () {

    foreach ([
                 \App\Models\Response::class,
                 \App\Models\Assessment::class,
                 \App\Models\AssessmentRater::class,
             ] as $model) {
        $model::query()->delete();
    }
    // User is NOT persisted — using make()
    $user = makeAuthUser();

    $this->be($user);

    // Rater
    $rater = Rater::factory()->create([
        'user_id' => $user->user_id,
    ]);

    // Framework + Assessment
    $framework = Framework::factory()->create();
    $assessment = Assessment::factory()->create([
        'framework_id' => $framework->id,
        'user_id' => $rater->user_id,
    ]);

    // Node + Question
    $nodeType = NodeType::factory()->create();
    $node = Node::factory()->create([
        'framework_id' => $framework->id,
        'node_type_id' => $nodeType->id,
    ]);

    $question1 = Question::factory()->create([
        'node_id' => $node->id,
    ]);
    $question2 = Question::factory()->create([
        'node_id' => $node->id,
    ]);

    // Scale + Option
    $scale = Scale::factory()->create();
    $scaleOption = ScaleOption::factory()->create(['scale_id' => $scale->id]);

    // Response for THIS assessment
    $responseA = Response::factory()->create([
        'assessment_id' => $assessment->id,
        'rater_id' => $rater->id,
        'question_id' => $question1->id,
        'scale_option_id' => $scaleOption->id,
    ]);

    // Another response for THIS assessment
    $responseB = Response::factory()->create([
        'assessment_id' => $assessment->id,
        'rater_id' => $rater->id,
        'question_id' => $question2->id,
        'scale_option_id' => $scaleOption->id,
    ]);

    // Response for a DIFFERENT assessment (should NOT appear)
    $framework1 = Framework::factory()->create();
    Response::factory()->create([
        'assessment_id' => Assessment::factory()->create(['framework_id' => $framework1->id, 'user_id' => $rater->user_id,])->id,
        'rater_id' => $rater->id,
        'question_id' => $question2->id,
        'scale_option_id' => $scaleOption->id,
    ]);

    $component = Livewire::test(Summary::class, [
        'assessmentId' => $assessment->id,
        'frameworkId'  => $framework->id,
    ]);
    $responses = $component->get('responses');
    $expectedIds = Response::where('assessment_id', $assessment->id)
        ->pluck('id')
        ->sort()
        ->values()
        ->toArray();

    $actualIds = collect($component->get('responses'))
        ->pluck('id')
        ->sort()
        ->values()
        ->toArray();


    $this->assertCount(2, $responses);
    $this->assertSame($expectedIds, $actualIds);
});

