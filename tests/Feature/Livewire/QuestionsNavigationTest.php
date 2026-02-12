<?php

use App\Enums\ResponseType;
use App\Livewire\Questions;
use App\Models\Assessment;
use App\Models\Framework;
use App\Models\Node;
use App\Models\NodeType;
use App\Models\Question;
use App\Models\Scale;
use App\Models\ScaleOption;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

function makeFrameworkGraph(User $user): array
{
    $framework = Framework::factory()->create();
    $nodeType = NodeType::factory()->create();

    $node1 = Node::factory()->for($framework)->create(['order' => 1, 'node_type_id' => $nodeType->id,]);
    $node2 = Node::factory()->for($framework)->create(['order' => 2, 'node_type_id' => $nodeType->id,]);

    $scale = Scale::factory()->create();
    $scaleOption = ScaleOption::factory()->create(['scale_id' => $scale->id]);

    // Create 2 required questions
    Question::factory()->create([
        'node_id'        => $node1->id,
        'response_type'  => ResponseType::TYPE_SCALE->value,
        'scale_id'       => $scale->id,
        'required'       => 1,
    ]);
    Question::factory()->create([
        'node_id'        => $node2->id,
        'response_type'  => ResponseType::TYPE_SCALE->value,
        'scale_id'       => $scale->id,
        'required'       => 1,
    ]);

    $assessment = Assessment::factory()
        ->for($framework)
        ->for($user)
        ->create();

    return [$framework, $assessment, collect([$node1, $node2])];
}

it('goPrevious moves to previous node and dispatches events when not on first node', function () {
    $user = makeAuthUser();
    actingAs($user);

    [$framework, $assessment, $nodes] = makeFrameworkGraph($user);

    // Start the component on the second node (index 1)
    $component = Livewire::test(Questions::class, [
        'assessmentId' => $assessment->id,
    ])->set('nodeKeyId', 1);

    // Sanity: currently at index 1
    expect($component->get('nodeKeyId'))->toBe(1);

    $component->call('goPrevious');

    // Assert we moved back to index 0
    expect($component->get('nodeKeyId'))->toBe(0);

    // Events are dispatched
    //$component->assertDispatched('questions-next-node', fn ($id) => $id === $nodes[0]->id);
    $component->assertDispatched('scroll-to-top');

    // No redirect in this branch
    $component->assertNoRedirect();
});

it('goPrevious on the first node redirects to variant selection', function () {
    $user = makeAuthUser();
    actingAs($user);

    [$framework, $assessment, $nodes] = makeFrameworkGraph($user);

    // On the very first node (index 0) → goPrevious() should trigger variant selection redirect
    Livewire::test(Questions::class, [
        'assessmentId' => $assessment->id,
    ])
        ->set('nodeKeyId', 0)
        ->call('goPrevious')
        ->assertRedirect(route('variants', [
            'frameworkId'  => $framework->id,
            'assessmentId' => $assessment->id,
            'back'         => 1,
        ]));
});

it('goToVariantSelection redirects to variants route', function () {
    $user = makeAuthUser();
    actingAs($user);

    [$framework, $assessment] = makeFrameworkGraph($user);

    Livewire::test(Questions::class, [
        'assessmentId' => $assessment->id,
    ])->call('goToVariantSelection')
        ->assertRedirect(route('variants', [
            'frameworkId'  => $framework->id,
            'assessmentId' => $assessment->id,
            'back'         => 1,
        ]));
});
