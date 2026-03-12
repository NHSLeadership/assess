<?php

use App\Livewire\Variants;
use App\Models\Assessment;
use App\Models\Framework;
use App\Models\Node;
use App\Models\NodeType;
use App\Models\Question;
use App\Models\Rater;
use App\Models\Response;
use App\Models\Scale;
use App\Models\ScaleOption;
use App\Models\User;
use Illuminate\Support\Collection;
use Livewire\Livewire;

/**
 * Underlying user factory helper used by other test helpers.
 * Use $persist = true to create a persisted user, false to make (non-persisted).
 *
 * @return mixed
 */
function testUserFactory(array $overrides = [], bool $persist = false)
{
    $defaults = [
        'preferred_username' => 'test-user',
        'user_id' => '1000000000',
    ];

    $attributes = array_merge($defaults, $overrides);

    if ($persist) {
        return User::factory()->create($attributes);
    }

    return User::factory()->make($attributes);
}

/**
 * Create a fake auth user (non-persisted). Use this as the canonical helper in tests.
 *
 * @return mixed
 */
function makeAuthUser(array $overrides = [])
{
    return testUserFactory($overrides, false);
}

/**
 * Create and persist an auth user (useful when tests expect a created user)
 *
 * @return mixed
 */
function createAuthUser(array $overrides = [])
{
    return testUserFactory($overrides, true);
}

/**
 * Create a framework with a node and questions useful for variants/resume tests.
 *
 * @return array
 */
function createFrameworkWithNodeAndQuestionsForVariants(int $questionCount = 2)
{
    $framework = Framework::factory()->create();
    $nodeType = NodeType::factory()->create();
    $node = Node::factory()->create([
        'framework_id' => $framework->id,
        'node_type_id' => $nodeType->id,
    ]);

    $questions = collect();
    for ($i = 0; $i < $questionCount; $i++) {
        $questions->push(Question::factory()->create([
            'node_id' => $node->id,
            'response_type' => 'scale',
            'required' => 1,
        ]));
    }
    $scale = Scale::factory()->create();
    $scaleOption = ScaleOption::factory()->create(['scale_id' => $scale->id]);

    return compact('framework', 'nodeType', 'node', 'questions', 'scale', 'scaleOption');
}

// Alias for existing name used in FrameworksFeatureTest
function createFrameworkWithNodeAndQuestions(int $questionCount = 2)
{
    return createFrameworkWithNodeAndQuestionsForVariants($questionCount);
}

/**
 * Create a more flexible framework/node/questions graph.
 *
 * @return array{framework: Framework, nodeType: NodeType, node: Node, questions: Collection}
 */
function createNodeWithQuestions(int $questionCount = 1, string $responseType = 'scale', array $overrides = [])
{
    $framework = $overrides['framework'] ?? Framework::factory()->create();
    $nodeType = NodeType::factory()->create();
    $node = Node::factory()->create(array_merge([
        'framework_id' => $framework->id,
        'node_type_id' => $nodeType->id,
    ], $overrides['node'] ?? []));

    $questions = collect();
    for ($i = 0; $i < $questionCount; $i++) {
        $questions->push(Question::factory()->create(array_merge([
            'node_id' => $node->id,
            'response_type' => $responseType,
        ], $overrides['question'] ?? [])));
    }

    return compact('framework', 'nodeType', 'node', 'questions');
}

/**
 * Create a scale with a single option and return both.
 *
 * @return array{scale: Scale, scaleOption: ScaleOption}
 */
function createScaleWithOption(array $scaleOverrides = [], array $optionOverrides = [])
{
    $scale = Scale::factory()->create($scaleOverrides);
    $scaleOption = ScaleOption::factory()->create(array_merge(['scale_id' => $scale->id], $optionOverrides));

    return compact('scale', 'scaleOption');
}

/**
 * Create a rater for a given user.
 *
 * @param  mixed  $user
 * @return mixed
 */
function variantsRaterForUser($user, array $overrides = [])
{
    return Rater::factory()->create(array_merge([
        'user_id' => $user->user_id,
    ], $overrides));
}

function raterForUser($user, array $overrides = [])
{
    return variantsRaterForUser($user, $overrides);
}

/**
 * Create a Livewire test instance for the Variants component and optionally act as a user
 *
 * @param  mixed|null  $user
 * @return mixed
 */
function variantsLivewireTest($user = null, array $params = [])
{
    if ($user) {
        Livewire::actingAs($user);
    }

    return Livewire::test(Variants::class, $params);
}

/**
 * Generic Livewire test helper that optionally acts as a given user and mounts any component class.
 *
 * @param  mixed|null  $user
 * @return mixed
 */
function livewireTest(string $componentClass, $user = null, array $params = [])
{
    if ($user) {
        Livewire::actingAs($user);
    }

    return Livewire::test($componentClass, $params);
}

/**
 * Create framework and a single variant attribute + option for reuse
 *
 * @return array
 */
function createFrameworkWithAttributeAndOption(string $key = 'stage', string $optionValue = 'Option A')
{
    $framework = Framework::factory()->create();

    $attribute = $framework->variantAttributes()->create([
        'key' => $key,
        'label' => ucfirst($key),
    ]);

    $option = $attribute->options()->create([
        'value' => $optionValue,
        'label' => $optionValue.' label',
    ]);

    return compact('framework', 'attribute', 'option');
}

/**
 * Centralise assessment creation for a given user & framework
 *
 * @param  mixed  $user
 * @param  mixed  $framework
 * @return mixed
 */
function createAssessmentForUser($user, $framework, array $overrides = [])
{
    return Assessment::factory()->create(array_merge([
        'framework_id' => $framework->id,
        'user_id' => $user->user_id,
    ], $overrides));
}

/**
 * Create a framework with a node and questions useful for variants/resume tests.
 *
 * @return array
 */
function createNodeAndQuestionsForFramework($framework, int $questionCount = 2, int $order = 1)
{
    $nodeType = NodeType::factory()->create();
    $node = Node::factory()->create([
        'framework_id' => $framework->id,
        'node_type_id' => $nodeType->id,
        'order' => $order,
    ]);

    $questions = collect();
    for ($i = 0; $i < $questionCount; $i++) {
        $questions->push(Question::factory()->create([
            'node_id' => $node->id,
            'response_type' => 'scale',
            'required' => 1,
        ]));
    }
    $scale = Scale::factory()->create();
    $scaleOption = ScaleOption::factory()->create(['scale_id' => $scale->id]);

    return compact('framework', 'nodeType', 'node', 'questions', 'scale', 'scaleOption');
}

/**
 * Create a response record for an assessment using the provided rater, question and scale option.
 *
 * @param  mixed  $assessment
 * @param  mixed  $rater
 * @param  mixed  $question
 * @param  mixed  $scaleOption
 * @return mixed
 */
function createResponseForAssessment($assessment, $rater, $question, $scaleOption, array $overrides = [])
{
    return Response::factory()->create(array_merge([
        'assessment_id' => $assessment->id,
        'rater_id' => $rater->id,
        'question_id' => $question->id,
        'scale_option_id' => $scaleOption->id,
    ], $overrides));
}
