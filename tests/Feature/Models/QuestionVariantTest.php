<?php

use App\Models\Framework;
use App\Models\NodeType;
use App\Models\Node;
use App\Models\Question;
use App\Models\QuestionVariant;
use App\Models\QuestionVariantMatch;
use App\Models\FrameworkVariantAttribute;
use App\Models\FrameworkVariantOption;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * Helper to build a QuestionVariant with its dependencies.
 */
function makeQuestionVariant(): QuestionVariant {
    $framework = Framework::factory()->create();
    $nodeType  = NodeType::factory()->create();

    $node = Node::factory()->create([
        'framework_id' => $framework->id,
        'node_type_id' => $nodeType->id,
        'name'         => 'Root Node',
        'description'  => 'Top level node',
    ]);

    $question = Question::factory()->create(['node_id' => $node->id]);

    return QuestionVariant::factory()->create(['question_id' => $question->id]);
}

/**
 * Helper to attach a match to a variant with given key/value.
 */
function attachMatch(QuestionVariant $variant, string $key, string $value): QuestionVariantMatch {
    $attribute = FrameworkVariantAttribute::factory()->create([
        'framework_id' => $variant->question->node->framework_id,
        'key'          => $key,
        'order'        => 1,
    ]);

    $option = FrameworkVariantOption::factory()->create([
        'framework_variant_attribute_id' => $attribute->id,
        'value'                          => $value,
    ]);

    return QuestionVariantMatch::factory()->create([
        'question_variant_id'            => $variant->id,
        'framework_variant_attribute_id' => $attribute->id,
        'framework_variant_option_id'    => $option->id,
    ]);
}

test('question variant can be created with factory', function () {
    $variant = makeQuestionVariant();

    expect($variant->exists)->toBeTrue()
        ->and($variant->question)->toBeInstanceOf(Question::class);
});

test('question variant can have many matches', function () {
    $variant = makeQuestionVariant();

    attachMatch($variant, 'color', 'red');
    attachMatch($variant, 'size', 'large');

    expect($variant->matches)->toHaveCount(2)
        ->and($variant->matches->first())->toBeInstanceOf(QuestionVariantMatch::class);
});

test('question variant conditionPairs returns attribute=option pairs', function () {
    $variant = makeQuestionVariant();

    attachMatch($variant, 'color', 'red');

    expect($variant->conditionPairs())->toEqual(['color=red']);
});

test('question variant conditionsSummary accessor returns formatted string', function () {
    $variant = makeQuestionVariant();

    attachMatch($variant, 'size', 'large');

    expect($variant->conditions_summary)->toEqual('size=large');
});
