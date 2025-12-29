<?php

namespace Tests\Support;

use App\Livewire\Questions;
use ArrayIterator;
use Illuminate\Support\Collection;

class QuestionsFakes
{
    public function __construct(
        public int $id,
        public string $name,
        public int $node_id
    ) {}
}

class FakeNode
{
    public function __construct(
        public int $id,
        public array $questions
    ) {}

    public function questions(): Collection
    {
        return collect($this->questions);
    }
}

class FakeFramework
{
    public function __construct(
        public array $nodes,
        public array $questions
    ) {}

    public function questions(): Collection
    {
        return collect($this->questions);
    }
}

class QuestionsProgressLabelFake extends Questions
{
    public $assessment;

    public function __construct($assessment)
    {
        $this->assessment = $assessment;
    }

    public function nodes(): ArrayIterator
    {
        return new ArrayIterator($this->assessment->framework->nodes);
    }

    // Override only the Eloquent-dependent part
    public function getQuestionProgressLabel(?int $questionId = null): string
    {
        $nodes = $this->nodes()->getArrayCopy();

        $currentQuestion = collect($this->assessment->framework->questions)
            ->firstWhere('id', $questionId);

        if (!$currentQuestion) {
            return '';
        }

        $questionCounter = 0;

        foreach ($nodes as $node) {
            if ($node->id === $currentQuestion->node_id) {
                $offset = collect($node->questions)
                    ->pluck('id')
                    ->search($questionId);

                $questionCounter += ($offset !== false ? $offset : 0);
                break;
            }

            $questionCounter += count($node->questions);
        }

        $currentNumber = $questionCounter + 1;
        $total = count($this->assessment->framework->questions);

        return "<strong>Question {$currentNumber} of {$total}</strong>";
    }
}
