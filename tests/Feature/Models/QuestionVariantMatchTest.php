<?php

use App\Models\Framework;
use App\Models\NodeType;
use App\Models\Node;
use App\Models\Question;
use App\Models\QuestionVariant;
use App\Models\FrameworkVariantAttribute;
use App\Models\FrameworkVariantOption;
use App\Models\QuestionVariantMatch;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function makeQuestionVariantMatch(): array {
    $framework = Framework::factory()->create();
    $nodeType  = NodeType::factory()->create();

    $node = Node::factory()->create([
        'framework_id' => $framework->id,
        'node_type_id' => $nodeType->id,
        'name'         => 'Root Node',
        'description'  => 'Top level node',
    ]);

    $question  = Question::factory()->create(['node_id' => $node->id]);
    $variant   = QuestionVariant::factory()->create(['question_id' => $question->id]);
    $attribute = FrameworkVariantAttribute::factory()->create(['framework_id' => $framework->id]);
    $option    = FrameworkVariantOption::factory()->create([
        'framework_variant_attribute_id' => $attribute->id,
    ]);

    $match = QuestionVariantMatch::factory()->create([
        'question_variant_id'            => $variant->id,
        'framework_variant_attribute_id' => $attribute->id,
        'framework_variant_option_id'    => $option->id,
    ]);

    return compact('framework', 'nodeType', 'node', 'question', 'variant', 'attribute', 'option', 'match');
}

test('question variant match can be created with factory', function () {
    $data = makeQuestionVariantMatch();

    expect($data['match']->exists)->toBeTrue()
        ->and($data['match']->question_variant_id)->toEqual($data['variant']->id)
        ->and($data['match']->framework_variant_attribute_id)->toEqual($data['attribute']->id)
        ->and($data['match']->framework_variant_option_id)->toEqual($data['option']->id);
});

test('question variant match belongs to a question variant', function () {
    $data = makeQuestionVariantMatch();

    expect($data['match']->variant)->toBeInstanceOf(QuestionVariant::class)
        ->and($data['match']->variant->id)->toEqual($data['variant']->id);
});

test('question variant match belongs to a framework variant attribute', function () {
    $data = makeQuestionVariantMatch();

    expect($data['match']->attribute)->toBeInstanceOf(FrameworkVariantAttribute::class)
        ->and($data['match']->attribute->id)->toEqual($data['attribute']->id);
});

test('question variant match belongs to a framework variant option', function () {
    $data = makeQuestionVariantMatch();

    expect($data['match']->option)->toBeInstanceOf(FrameworkVariantOption::class)
        ->and($data['match']->option->id)->toEqual($data['option']->id);
});
