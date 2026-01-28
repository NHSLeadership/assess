<?php

use Tests\Support\{
    QuestionsFakes,
    FakeNode,
    FakeFramework,
    QuestionsProgressLabelFake
};

it('returns correct progress label for a question', function () {

    $q1 = new QuestionsFakes(10, 'q1', 1);
    $q2 = new QuestionsFakes(11, 'q2', 1);
    $q3 = new QuestionsFakes(20, 'q3', 2);

    $node1 = new FakeNode(1, [$q1, $q2]);
    $node2 = new FakeNode(2, [$q3]);

    $framework = new FakeFramework(
        nodes: [$node1, $node2],
        questions: [$q1, $q2, $q3]
    );

    $assessment = new \stdClass();
    $assessment->framework = $framework;

    $component = new QuestionsProgressLabelFake($assessment);

    $label = $component->getQuestionProgressLabel(11);

    expect($label)->toBe('<strong>Question 2 of 3</strong>');
});
