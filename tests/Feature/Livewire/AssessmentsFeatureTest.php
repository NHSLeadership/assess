<?php

use App\Livewire\Assessments;
use App\Models\Assessment;
use App\Models\AssessmentRater;
use App\Models\Framework;
use App\Models\Node;
use App\Models\NodeType;
use App\Models\Rater;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('redirects to frameworks when assessmentId is invalid', function () {
    Livewire::test(Assessments::class, [
        'assessmentId' => null,
    ])->assertRedirect(route('frameworks'));
});

it('renders successfully with a valid assessment', function () {
    $user = makeAuthUser();
    $this->actingAs($user);

    $framework = Framework::factory()->create();
    $assessment = Assessment::factory()
        ->for($framework)
        ->for($user)
        ->create();

    Livewire::test(Assessments::class, [
        'assessmentId' => $assessment->id,
    ])->assertOk();
});

it('loads paginated nodes when assessment has nodes with questions', function () {
    $user = makeAuthUser();
    $this->actingAs($user);

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

    Livewire::test(Assessments::class, [
        'assessmentId' => $assessment->id,
    ])
        ->assertViewHas('paginatedNodes');
});

it('shows no-questions message when framework has no questions', function () {
    $noQuestionsMessage = __('pages.assessments.empty_state.no_questions');
    $notFoundMessage = __('pages.assessments.empty_state.not_found');

    $user = makeAuthUser();
    $this->actingAs($user);

    $framework = Framework::factory()->create();
    $assessment = Assessment::factory()
        ->for($framework)
        ->for($user)
        ->create();

    Livewire::test(Assessments::class, [
        'assessmentId' => $assessment->id,
    ])
        ->assertSee($noQuestionsMessage)
        ->assertDontSee($notFoundMessage);
});

it('shows not-found message when framework context is missing', function () {
    $noQuestionsMessage = __('pages.assessments.empty_state.no_questions');
    $notFoundMessage = __('pages.assessments.empty_state.not_found');

    $user = makeAuthUser();
    $this->actingAs($user);

    $framework = Framework::factory()->create();
    $assessment = Assessment::factory()
        ->for($framework)
        ->for($user)
        ->create();

    $rater = Rater::factory()->create([
        'subject_id' => $user->user_id,
    ]);

    AssessmentRater::factory()->create([
        'assessment_id' => $assessment->id,
        'rater_id' => $rater->id,
        'submitted_at' => null,
    ]);

    $framework->delete();

    Livewire::test(Assessments::class, [
        'assessmentId' => $assessment->id,
        'raterId' => $rater->id,
    ])
        ->assertSee($notFoundMessage)
        ->assertDontSee($noQuestionsMessage);
});

it('updates currentNode when questions-next-node event is dispatched', function () {
    $user = makeAuthUser();
    $this->actingAs($user);

    $framework = Framework::factory()->create();
    $assessment = Assessment::factory()
        ->for($framework)
        ->for($user)
        ->create();

    $nodeType = NodeType::factory()->create();
    $node = Node::factory()->create([
        'framework_id' => $framework->id,
        'node_type_id' => $nodeType->id,
    ]);

    $component = Livewire::test(Assessments::class, [
        'assessmentId' => $assessment->id,
    ]);

    $component->dispatch('questions-next-node', nodeId: $node->id);

    expect($component->instance()->currentNode->id)->toBe($node->id);
});
