<?php

use App\Models\Assessment;
use App\Models\User;
use App\Models\Framework;
use App\Models\Response;
use App\Models\Rater;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Common setup for all tests
    $this->user = User::factory()->make([
        'user_id' => '1000000000',
    ]);

    $this->framework = Framework::factory()->create();
    $this->assessment = Assessment::factory()->create([
        'framework_id' => $this->framework->id,
        'user_id'      => $this->user->id,
    ]);
});

test('assessment belongs to a framework', function () {
    expect($this->assessment->framework->id)->toEqual($this->framework->id);
});

test('assessment has many responses', function () {
    $rater = Rater::factory()->create(['user_id' => $this->user->user_id]);

    $nodeType = \App\Models\NodeType::factory()->create();
    $node = \App\Models\Node::factory()->create([
        'framework_id'  => $this->framework->id,
        'node_type_id'  => $nodeType->id,
    ]);

    $questions = \App\Models\Question::factory()
        ->count(3)
        ->create(['node_id' => $node->id, 'response_type' => 'scale']);

    $scale = \App\Models\Scale::factory()->create();

    $option = \App\Models\ScaleOption::factory()->create([
        'scale_id' => $scale->id,
        'label'    => 'Good',
        'value'    => 3,
        'order'    => 1,
    ]);
    foreach ($questions as $question) {
        Response::factory()->create([
            'assessment_id' => $this->assessment->id,
            'rater_id'      => $rater->id,
            'question_id'   => $question->id,
            'scale_option_id' => $option->id,
        ]);
    }

    expect($this->assessment->responses)->toHaveCount(3);
});

test('assessment raters relationship works', function () {
    $rater = Rater::factory()->create(['user_id' => $this->user->user_id]);

    $this->assessment->raters()->attach($rater->id, [
        'role'    => 'manager',
        'is_self' => false,
    ]);

    $pivot = $this->assessment->raters()->first()->pivot;

    expect($pivot->role)->toEqual('manager')
        ->and($pivot->is_self)->toBeFalse();
});

test('assessment casts submitted_at to Carbon when persisted', function () {
    $assessment = Assessment::factory()->create([
        'framework_id' => $this->framework->id,
        'user_id'      => $this->user->id,
        'submitted_at' => '2025-12-18 14:00:00',
    ]);

    expect($assessment->submitted_at)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
});
