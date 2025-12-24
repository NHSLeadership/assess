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

    $user = User::factory()->make([
        'preferred_username' => 'test-user',
        'user_id' => '1000000000',
    ]);
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
    $user = User::factory()->make([
        'preferred_username' => 'test-user',
        'user_id' => '1000000000',
    ]);

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
    $user = User::factory()->make([
        'preferred_username' => 'test-user',
        'user_id' => '1000000000',
    ]);

    // Authenticate the made user
    $this->be($user);

    $framework = Framework::factory()->create();
    $assessment = Assessment::factory()
        ->for($framework)
        ->for($user)
        ->create();

    $framework = Framework::factory()->create();
    $nodeType = NodeType::factory()->create();
    $node = Node::factory()->create([
        'framework_id' => $framework->id,
        'node_type_id' => $nodeType->id,
    ]);
    $node->questions()->create(['text' => 'Q1', 'order' => 1, 'node_id' => $node->id, 'title' => 'Question 1']);

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
    $user = User::factory()->make([
        'preferred_username' => 'test-user',
        'user_id' => '1000000000',
    ]);

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

    // Node + Question
    $nodeType = NodeType::factory()->create();
    $node = Node::factory()->create([
        'framework_id' => $framework->id,
        'node_type_id' => $nodeType->id,
    ]);

    $question = Question::factory()->create([
        'node_id' => $node->id,
    ]);

    // Scale + Option
    $scale = Scale::factory()->create();
    $scaleOption = ScaleOption::factory()->create(['scale_id' => $scale->id]);

    // Response
    Response::factory()->create([
        'assessment_id' => $assessment->id,
        'rater_id' => $rater->id,
        'question_id' => $question->id,
        'scale_option_id' => $scaleOption->id,
    ]);

    Livewire::test(Summary::class, [
        'assessmentId' => $assessment->id,
        'frameworkId' => $framework->id,
    ])
        ->call('continueAssessment')
        ->assertRedirect(route('frameworks'));
});

it('surfaces an error and scrolls to top when assessment is already submitted', function () {

    // User is NOT persisted — using make()
    $user = User::factory()->make([
        'preferred_username' => 'test-user',
        'user_id' => '1000000000',
    ]);

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
    $user = User::factory()->make([
        'preferred_username' => 'test-user',
        'user_id' => '1000000000',
    ]);

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
    $user = User::factory()->make([
        'preferred_username' => 'test-user',
        'user_id' => '1000000000',
    ]);

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
    $user = User::factory()->make([
        'preferred_username' => 'test-user',
        'user_id' => '1000000000',
    ]);

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
    $user = User::factory()->make([
        'preferred_username' => 'test-user',
        'user_id' => '1000000000',
    ]);

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

    $user = User::factory()->make([
        'preferred_username' => 'test-user',
        'user_id' => '1000000000',
    ]);

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