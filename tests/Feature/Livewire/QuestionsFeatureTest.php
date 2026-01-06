<?php

use App\Enums\ResponseType;
use App\Livewire\Questions;
use App\Models\Assessment;
use App\Models\AssessmentRater;
use App\Models\Framework;
use App\Models\Node;
use App\Models\NodeType;
use App\Models\Question;
use App\Models\Rater;
use App\Models\Response;
use App\Models\Scale;
use App\Models\ScaleOption;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);



it('redirects to summary when assessment is submitted', function () {
    $user = makeAuthUser();
    Livewire::actingAs($user);
    $rater = raterForUser($user);

    $setup = createFrameworkWithNodeAndQuestions();

    // Assessment marked as submitted
    $assessment = Assessment::factory()->create([
        'framework_id' => $setup['framework']->id,
        'user_id'      => $user->user_id,
        'submitted_at'    => now(),
    ]);



    Livewire::test(\App\Livewire\Questions::class, [
        'assessmentId' => $assessment->id,
    ])
        ->assertRedirect(route('summary', [
            'frameworkId'  => $setup['framework']->id,
            'assessmentId' => $assessment->id,
        ]));
});

it('redirects to summary when assessment is finished', function () {
    $user = makeAuthUser();
    Livewire::actingAs($user);
    $rater = raterForUser($user);

    $setup = createFrameworkWithNodeAndQuestions(1);

    // Assessment marked as finished
    $assessment = Assessment::factory()->create([
        'framework_id' => $setup['framework']->id,
        'user_id'      => $user->user_id,
    ]);

    $response = createResponseForAssessment($assessment, $rater, $setup['questions'][0], $setup['scaleOption']);

    Livewire::test(\App\Livewire\Questions::class, [
        'assessmentId' => $assessment->id,
    ])
        ->assertRedirect(route('summary', [
            'frameworkId'  => $setup['framework']->id,
            'assessmentId' => $assessment->id,
        ]));
});

it('jumps to the specified node when nodeId is provided', function () {
    $user = makeAuthUser();
    Livewire::actingAs($user);
    $rater = raterForUser($user);

    // Framework
    $framework = Framework::factory()->create();

//
    $setup2 = createNodeAndQuestionsForFramework($framework, 2, 1);
    $setup2 = createNodeAndQuestionsForFramework($framework, 2, 2);

    // Assessment
    $assessment = Assessment::factory()->create([
        'framework_id' => $framework->id,
        'user_id'      => $user->user_id,
    ]);

    // Mount component with nodeId = nodeB
    Livewire::test(\App\Livewire\Questions::class, [
        'assessmentId' => $assessment->id,
        'nodeId'       => $setup2['node']->id,
    ])
        ->assertSet('nodeKeyId', 1);

});

it('moves to the next node when on the last page', function () {
    $user = makeAuthUser();
    Livewire::actingAs($user);

    // Framework
    $framework = Framework::factory()->create();

    // Node A (first node)
    $nodeType = NodeType::factory()->create();
    $nodeA = Node::factory()->create([
        'framework_id' => $framework->id,
        'node_type_id' => $nodeType->id,
        'order' => 1,
    ]);

    // Node B (second node)
    $nodeB = Node::factory()->create([
        'framework_id' => $framework->id,
        'node_type_id' => $nodeType->id,
        'order' => 2,
    ]);

    // Scale + option
    $scale = Scale::factory()->create();
    $scaleOption = ScaleOption::factory()->create(['scale_id' => $scale->id]);

    // Node A has exactly 3 questions (fits on one page)
    $questionsA = Question::factory()->count(3)->create([
        'node_id'        => $nodeA->id,
        'response_type'  => ResponseType::TYPE_SCALE->value,
        'scale_id'       => $scale->id,
    ]);

    // Node B has 1 question (not relevant for this test)
    Question::factory()->create([
        'node_id'        => $nodeB->id,
        'response_type'  => ResponseType::TYPE_SCALE->value,
        'scale_id'       => $scale->id,
    ]);

    // Assessment
    $assessment = Assessment::factory()->create([
        'framework_id' => $framework->id,
        'user_id'      => $user->user_id,
    ]);

    // Rater (must match component logic)
    $rater = Rater::factory()->create(['user_id' => $user->user_id]);
    AssessmentRater::factory()->create([
        'assessment_id' => $assessment->id,
        'rater_id'      => $rater->id,
    ]);

    // Prepare data for all 3 questions on node A
    $data = [];
    foreach ($questionsA as $q) {
        $data["question_{$q->id}"] = $scaleOption->id;
    }

    $test = Livewire::test(\App\Livewire\Questions::class, [
        'assessmentId' => $assessment->id,
    ]);

    $test->set('data', $data)
        ->call('storeNext');

    $this->assertDatabaseHas('responses', [
        'assessment_id'   => $assessment->id,
        'question_id'     => $questionsA[0]->id,
        'scale_option_id' => $scaleOption->id,
    ]);
});

it('shows validation errors when required questions are unanswered', function () {
    $user = makeAuthUser();
    Livewire::actingAs($user);

    // Framework
    $framework = Framework::factory()->create();

    // Node
    $nodeType = NodeType::factory()->create();
    $node = Node::factory()->create([
        'framework_id' => $framework->id,
        'node_type_id' => $nodeType->id,
        'order' => 1,
    ]);

    // Scale + option
    $scale = Scale::factory()->create();
    $scaleOption = ScaleOption::factory()->create(['scale_id' => $scale->id]);

    // Create 2 required questions
    $questions = Question::factory()->count(2)->create([
        'node_id'        => $node->id,
        'response_type'  => ResponseType::TYPE_SCALE->value,
        'scale_id'       => $scale->id,
        'required'       => 1,
    ]);

    // Assessment
    $assessment = Assessment::factory()->create([
        'framework_id' => $framework->id,
        'user_id'      => $user->user_id,
    ]);

    $test = Livewire::test(\App\Livewire\Questions::class, [
        'assessmentId' => $assessment->id,
    ]);

    // Submit with no answers
    $test->call('storeNext')
        ->assertHasErrors([
            'data.question_' . $questions[0]->id,
            'data.question_' . $questions[1]->id,
        ]);
});


