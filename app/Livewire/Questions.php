<?php

namespace App\Livewire;

use App\Enums\ResponseType;
use App\Models\Node;
use App\Models\Response;
use App\Services\FrameworkTraversalService;
use App\Services\UserResponseService;
use App\Traits\AssessmentHelperTrait;
use App\Traits\FormFieldValidationRulesTrait;
use App\Traits\UserTrait;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Questions extends Component
{
    use AssessmentHelperTrait;
    use FormFieldValidationRulesTrait;
    use UserTrait;
    use WithoutUrlPagination;
    use WithPagination;

    public $assessmentId;

    protected $perPage = 3;

    protected $pageName = 'questionId';

    public ?array $data = [];

    public ?int $nodeKeyId = null;

    public ?int $nodeId = null;

    public ?string $edit = null;

    public function mount(): void
    {
        // Redirect if not permitted to do an assessment for this framework now
        $this->redirectIfAssessmentNotPermitted($this->assessment()?->framework?->id, $this->assessmentId);

        $this->redirectIfSubmittedOrFinished($this->assessment(), $this->assessment()?->framework->id, $this->edit);

        /**
         * Pre-populate forms with defaults
         */
        $this->data = $this->responses()?->toArray() ?? [];
        if (empty($this->data) && $this->nodeQuestions()->isNotEmpty()) {
            foreach ($this->nodeQuestions() as $question) {
                $defaults = null;
                // This may be a match/switch expression in future based on multiple response_types
                if ($question->response_type !== ResponseType::TYPE_TEXTAREA->value) {
                    $defaults = unserialize($question['defaults']) ?? null;
                }
                $this->data["question_{$question->id}"] = $defaults;
            }
        }

        if ($this->nodeId !== null && $this->nodeId !== 0 && $this->edit === 'edit') {
            // Explicit edit link from Summary -> honour it
            $this->goToNodeById($this->nodeId);
        } else {
            // Normal flow (new or existing): always resume to first unanswered in traversal order
            $resumeNodeId = $this->findResumeNodeId();
            if ($resumeNodeId) {
                $this->goToNodeById($resumeNodeId);
            }
        }

        $this->dispatch('questions-next-node', $this->node()?->id);
    }
    protected function orderedQuestions(?Node $node)
    {
        return $node?->questions()
            ->where('active', true)
            ->orderBy('order')
            ->orderBy('id');
    }

    public function nodeQuestions(): Collection
    {
        return $this->orderedQuestions($this->node())?->get() ?? collect();
    }

    protected function messages(): array
    {
        foreach ($this->nodeQuestions() as $question) {
            $messages['data.'.$question['name'].'.numeric'] = 'Select one of the following options';
            if ($question->response_type !== ResponseType::TYPE_TEXTAREA->value) {
                $messages['data.'.$question['name'].'.required'] = 'Select one of the following options';
            } else {
                $messages['data.'.$question['name'].'.required'] = 'Enter your response';
                $messages['data.'.$question['name'].'.max'] =
                    'Your response must be less than '.
                    $this->getMaxLengthForType(ResponseType::TYPE_TEXTAREA->value).' characters';
            }
        }

        return $messages ?? [];
    }

    public function node(): ?Node
    {
        return $this->nodes()->current();
    }

    #[Computed]
    public function nodes(): ?\ArrayIterator
    {
        if (empty($this->assessment->framework)) {
            return null;
        }

        // Single source of truth: depth-first, sibling-ordered, question-bearing nodes
        $nodes = app(FrameworkTraversalService::class)
            ->orderedQuestionNodes($this->assessment->framework->id);

        /** @var \ArrayIterator<int, \App\Models\Node> $nodesIterator */
        $nodesIterator = $nodes->values()->getIterator();

        // Initialise pointer (nodeKeyId is the index in the ordered list)
        if ($this->nodeKeyId === null) {
            $this->nodeKeyId = $nodesIterator->key();
        }

        $nodesIterator->seek($this->nodeKeyId);

        return $nodesIterator;
    }

    #[Computed]
    public function responses(): ?Collection
    {
        return $this->buildResponses(false);
    }

    #[Computed]
    public function requiredResponses(): ?Collection
    {
        return $this->buildResponses(true);
    }

    public function buildResponses(bool $onlyRequired = false): ?Collection
    {
        $assessment = $this->user()?->assessments()
            ->where('id', $this->assessmentId)
            ->with('responses.question')
            ->first();

        if (! $assessment) {
            return null;
        }


        $assessment = $this->assessment();

        if (!$assessment instanceof \App\Models\Assessment) {
            return null;
        }
        /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\Response> $responses */
        $responses = $assessment->responses;


        if ($onlyRequired) {
            $responses = $responses->filter(fn($response) => $response->question->required ?? false);
        }

        return $responses->mapWithKeys(
            /** @param \App\Models\Response $response */
            function (\App\Models\Response $response) use ($onlyRequired): array {
                $key = $response->question->name;

                // TEXTAREA → only one value
                if ($response->question->response_type === ResponseType::TYPE_TEXTAREA->value) {
                    return [
                        $key => $response->textarea ?? '',
                    ];
                }

                // SCALE
                if ($onlyRequired) {
                    return [
                        $key => $response->scale_option_id,
                    ];
                }
                return [
                    $key => $response->scale_option_id,
                    $key.'_reflection' => $response->textarea ?? '',
                ];
            }
        );
    }

    public function backPage(): void
    {
        $this->previousPage();
    }

    #[\Override]
    public function getRules(): array
    {
        $rules = [];
        foreach ($this->paginatedQuestions() as $question) {
            $rules['data.'.$question['name']] = $this->getRulesForType($question);
        }

        return $rules ?? [];
    }

    public function rater()
    {
        return $this->assessment()?->raters()?->first();
    }

    /**
     * Store responses and go to next question or node
     */
    public function storeNext(): void
    {
        $this->validateAndSaveResponses();

        if ($this->paginatedQuestions()->hasMorePages()) {
            $this->nextPage(pageName: $this->pageName);
        } else {
            $this->resetPage(pageName: $this->pageName);
            $this->nodes->next();

            if (! $this->nodes->valid()) {
                $this->finishAssessment();
                return;
            }

            $this->nodeKeyId = $this->nodes->key();
        }

        $this->dispatch('questions-next-node', $this->node()?->id);
        $this->dispatch('scroll-to-top');
    }

    /**
     * Validate and save user responses
     *
     * @throws ValidationException
     */
    private function validateAndSaveResponses(): void
    {
        $rules = $this->getRules();

        if ($rules !== []) {
            try {
                $this->validate($rules);
            } catch (ValidationException $e) {
                $this->dispatch('scroll-to-top');
                throw $e;
            }
        }

        $questions = $this->nodeQuestions()->keyBy('name');

        foreach (($this->data ?? []) as $name => $value) {

            // Skip reflection keys entirely
            if (str_ends_with((string) $name, '_reflection')) {
                continue;
            }

            // Skip unknown questions
            if (! isset($questions[$name])) {
                continue;
            }

            $question = $questions[$name];

            // Skip empty textarea responses
            if (
                $question['response_type'] === ResponseType::TYPE_TEXTAREA->value &&
                in_array(trim((string) $value), ['', '0'], true)
            ) {
                continue;
            }

            // Save main response (scale or textarea)
            UserResponseService::updateOrCreate(
                $value,
                $question,
                $this->assessmentId,
                $this->rater()?->id
            );

            // Save optional reflection for scale questions
            if ($question['response_type'] === ResponseType::TYPE_SCALE->value) {

                $reflectionKey = $name.'_reflection';
                $reflection = trim($this->data[$reflectionKey] ?? '');

                Response::updateOrCreate(
                    [
                        'assessment_id' => $this->assessmentId,
                        'question_id' => $question->id,
                        'rater_id' => $this->rater()?->id,
                    ],
                    [
                        'textarea' => $reflection,
                    ]
                );
            }
        }
    }

    /**
     * Get question progress label
     */
    public function getQuestionProgressLabel(?int $questionId = null): string
    {
        $nodes = $this->nodes()->getArrayCopy();

        $currentQuestion = $this->assessment?->framework
            ->questions()
            ->where('active', true)
            ->where('questions.id', $questionId)
            ->first();

        if (! $currentQuestion) {
            return '';
        }

        $questionCounter = 0;

        foreach ($nodes as $node) {
            if ($node->id === $currentQuestion->node_id) {
                $offset = $this->orderedQuestions($node)
                    ->pluck('id')
                    ->search(fn ($id): bool => (int) $id === (int) $questionId);

                $questionCounter += ($offset !== false ? $offset : 0);
                break;
            }

            $questionCounter += $this->orderedQuestions($node)->count();
        }

        $currentNumber = $questionCounter + 1;
        $total = $this->assessment?->framework
            ->questions()
            ->where('active', true)
            ->count() ?? 0;

        return "<strong>Response {$currentNumber} of {$total}</strong>";
    }

    /**
     * Go to previous question or node
     */
    public function goPrevious(): void
    {
        $this->resetPage(pageName: $this->pageName);

        if ($this->nodeKeyId > 0) {
            $this->nodes->seek($this->nodeKeyId - 1);
            $this->nodeKeyId = $this->nodes->key();
        } else {
            $this->goToVariantSelection();
        }

        $this->dispatch('questions-next-node', $this->node()?->id);
        $this->dispatch('scroll-to-top');
    }

    /**
     * Finish the assessment and go to summary page
     */
    public function finishAssessment()
    {
        $this->validateAndSaveResponses();

        // Additional logic for finishing the assessment can be added here
        return redirect()->route(
            'summary',
            ['frameworkId' => $this->assessment?->framework->id, 'assessmentId' => $this->assessmentId]
        );
    }

    /**
     * Go to variant selection page
     */
    public function goToVariantSelection()
    {
        return redirect()
            ->route(
                'variants',
                [
                    'frameworkId' => $this->assessment?->framework->id,
                    'assessmentId' => $this->assessmentId,
                    'back' => 1,
                ]
            );
    }

    protected function findResumeNodeId(): ?int
    {
        $framework = $this->assessment?->framework;

        if (! $framework) {
            return null;
        }

        $orderedNodes = app(FrameworkTraversalService::class)
            ->orderedQuestionNodes($framework->id);

        // Question IDs already answered in this assessment
        $answeredQuestionIds = Response::where('assessment_id', $this->assessmentId)
            ->pluck('question_id')
            ->all();

        foreach ($orderedNodes as $node) {
            $questionIds = $this->orderedQuestions($node)
                ->pluck('id')
                ->all();

            // if any question in this node is unanswered, resume here
            if (count(array_diff($questionIds, $answeredQuestionIds)) > 0) {
                return $node->id;
            }
        }

        return null;
    }

    /**
     * Go to a specific node by its id
     */
    public function goToNodeById(int $nodeId): void
    {
        if ($nodeId === 0) {
            return;
        }
        $this->resetPage(pageName: $this->pageName);

        $nodes = $this->nodes();

        if (!$nodes instanceof \ArrayIterator) {
            return;
        }

        $nodes->rewind();

        foreach ($nodes as $index => $node) {
            if ($node->id === $nodeId) {
                $nodes->seek($index);
                $this->nodeKeyId = $index;
                break;
            }
        }

        $this->dispatch('questions-next-node', $this->node()?->id);
        $this->dispatch('scroll-to-top');
    }

    protected function paginatedQuestions(): ?LengthAwarePaginator
    {
        return $this->orderedQuestions($this->node())
            ?->paginate($this->perPage, pageName: $this->pageName);
    }

    /**
     * Build scale options for a question
     *
     * @param  mixed  $question
     */
    public function buildScaleOptions($question): array
    {
        if (! $question?->scale) {
            return [];
        }

        return $question->scale->options()
            ->orderBy('order')
            ->get()
            ->mapWithKeys(function ($opt): array {
                $label = $opt->label;

                if (! empty($opt->description)) {
                    $label .= ' - '.$opt->description;
                }

                return [$opt->id => $label];
            })
            ->toArray();
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.questions', [
            'questions' => $this->paginatedQuestions(),
        ]);
    }
}
