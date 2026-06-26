<?php

namespace App\Livewire;

use App\Enums\ResponseType;
use App\Models\Assessment;
use App\Models\AssessmentRater;
use App\Models\Node;
use App\Models\Rater;
use App\Models\Response;
use App\Services\FrameworkTraversalService;
use App\Services\QuestionTextResolver;
use App\Services\UserResponseService;
use App\Traits\AssessmentHelperTrait;
use App\Traits\FormFieldValidationRulesTrait;
use App\Traits\UserTrait;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Questions extends Component
{
    use AssessmentHelperTrait {
        assessment as getAssessment;
    }
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
    public ?int $raterId = null;

    public ?string $edit = null;
    public array $resolvedQuestionTexts = [];
    protected ?Assessment $cachedAssessment = null;
    protected ?Rater $cachedRater = null;
    protected ?AssessmentRater $cachedAssessmentRater = null;
    protected ?\ArrayIterator $cachedNodes = null;

    public function assessment(): ?Assessment
    {
        if ($this->cachedAssessment !== null) {
            return $this->cachedAssessment;
        }

        // Call the trait method directly via alias
        return $this->cachedAssessment = $this->getAssessment();
    }


    public function mount(): void
    {
        //Skip these checks for raters
        if (empty($this->raterId)) {
            // Redirect if not permitted to do an assessment for this framework now
            $this->redirectIfAssessmentNotPermitted($this->assessment()?->framework?->id, $this->assessmentId);
            $this->redirectIfSubmittedOrFinished($this->assessment(), $this->assessment()?->framework->id, $this->edit);
        }

        if (! empty($this->raterId) && ! $this->assessmentRater()) {
            abort(404);
        }

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
        $questions = $this->node()?->questions ?? collect();

        $resolvedTexts = $this->resolvedQuestionTextMap();

        return $questions
            ->filter(fn ($question) => array_key_exists($question->id, $resolvedTexts))
            ->map(function ($question) use ($resolvedTexts) {
                $question->resolved_text = $resolvedTexts[$question->id];

                return $question;
            })
            ->values();
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
        if ($this->cachedNodes !== null) {
            return $this->cachedNodes;
        }

        if (empty($this->assessment()?->framework)) {
            return null;
        }

        $nodes = app(FrameworkTraversalService::class)
            ->orderedQuestionNodes($this->assessment()->framework->id)
            ->filter(fn (Node $node) => $this->nodeHasVisibleQuestions($node))
            ->values();

        $nodesIterator = $nodes->getIterator();

        if ($nodes->isNotEmpty()) {
            $this->nodeKeyId = $this->nodeKeyId ?? 0;
            $nodesIterator->seek($this->nodeKeyId);
        }

        return $this->cachedNodes = $nodesIterator;
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
        $assessment = $this->assessment();

        if (! $assessment instanceof \App\Models\Assessment) {
            return null;
        }

        $currentRaterId = $this->currentResponseRaterId();

        if ($currentRaterId === null) {
            return collect();
        }

        $responses = Response::query()
            ->where('assessment_id', $assessment->id)
            ->where('rater_id', $currentRaterId)
            ->with('question')
            ->get();

        if ($onlyRequired) {
            $responses = $responses->filter(fn ($response) => $response->question->required ?? false);
        }

        return $responses->mapWithKeys(function (\App\Models\Response $response) use ($onlyRequired): array {
            $key = $response->question->name;

            if ($response->question->response_type === ResponseType::TYPE_TEXTAREA->value) {
                return [
                    $key => $response->textarea ?? '',
                ];
            }

            if ($onlyRequired) {
                return [
                    $key => $response->scale_option_id,
                ];
            }

            return [
                $key => $response->scale_option_id,
                $key . '_reflection' => $response->textarea ?? '',
            ];
        });
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

    // For now only get self rater. Later add function to get external raters
    public function selfRater(): ?Rater
    {
        if ($this->cachedRater !== null) {
            return $this->cachedRater;
        }

        $assessment = $this->assessment();

        if (! $assessment instanceof Assessment) {
            return null;
        }

        return $this->cachedRater =
            Rater::where('subject_id', $assessment->user_id)->orderBy('id')->first();
    }


    protected function currentResponseRaterId(): ?int
    {
        return ! empty($this->raterId)
            ? $this->raterId
            : $this->selfRater()?->id;
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

        $raterId = $this->currentResponseRaterId();

        if ($raterId === null) {
            throw ValidationException::withMessages([
                'rater' => 'Unable to determine who is responding to this assessment.',
            ]);
        }

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
                $raterId
            );

            // Save optional reflection for scale questions
            if ($question['response_type'] === ResponseType::TYPE_SCALE->value) {

                $reflectionKey = $name.'_reflection';
                $reflection = trim($this->data[$reflectionKey] ?? '');

                Response::updateOrCreate(
                    [
                        'assessment_id' => $this->assessmentId,
                        'question_id' => $question->id,
                        'rater_id' => $raterId,
                    ],
                    [
                        'textarea' => $reflection,
                    ]
                );
            }
        }
    }

    #[Computed]
    public function visibleRequiredCount(): int
    {
        $resolvedTexts = $this->resolvedQuestionTextMap();

        return $this->assessment()?->framework
            ?->questions()
            ->where('required', true)
            ->whereIn('questions.id', array_keys($resolvedTexts))
            ->count() ?? 0;
    }

    /**
     * Get question progress label
     */
    public function getQuestionProgressLabel(?int $questionId = null): string
    {
        $nodes = $this->nodes()->getArrayCopy();
        $resolvedTexts = $this->resolvedQuestionTextMap();

        $currentQuestion = $this->assessment()?->framework
            ->questions()
            ->where('active', true)
            ->where('questions.id', $questionId)
            ->first();

        if (! $currentQuestion) {
            return '';
        }

        $questionCounter = 0;

        foreach ($nodes as $node) {
            $visibleQuestions = $node->questions
                ->filter(fn ($q) => array_key_exists($q->id, $resolvedTexts));

            if ($node->id === $currentQuestion->node_id) {
                $questionIds = $visibleQuestions->pluck('id');

                $offset = $questionIds->search(
                    fn ($id) => (int) $id === (int) $questionId
                );

                $questionCounter += ($offset !== false ? $offset : 0);
                break;
            }

            $questionCounter += $visibleQuestions->count();
        }

        $currentNumber = $questionCounter + 1;
        $total = count($resolvedTexts);

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

        if (!empty($this->raterId)) {
            $url = URL::signedRoute('assessment-rater-summary', [
                'frameworkId' => $this->assessment()?->framework->id,
                'assessmentId' => $this->assessmentId,
                'raterId' => $this->raterId,
            ]);
            return redirect()->to($url);
        }
        // Additional logic for finishing the assessment can be added here
        return redirect()->route(
            'summary',
            ['frameworkId' => $this->assessment()?->framework->id, 'assessmentId' => $this->assessmentId]
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
                    'frameworkId' => $this->assessment()?->framework->id,
                    'assessmentId' => $this->assessmentId,
                    'back' => 1,
                ]
            );
    }

    protected function findResumeNodeId(): ?int
    {
        $nodesIterator = $this->nodes();

        if (! $nodesIterator instanceof \ArrayIterator) {
            return null;
        }

        // Use the same resolved question set used elsewhere in this component.
        $visibleQuestionIds = array_keys($this->resolvedQuestionTextMap());
        $visibleQuestionIdLookup = array_flip($visibleQuestionIds);

        $currentRaterId = $this->currentResponseRaterId();

        if ($currentRaterId === null) {
            return null;
        }

        // Question IDs already answered in this assessment.
        $answeredQuestionIds = Response::where('assessment_id', $this->assessmentId)
            ->where('rater_id', $currentRaterId)
            ->pluck('question_id')
            ->all();

        foreach ($nodesIterator->getArrayCopy() as $node) {
            $questionIds = $node->questions
                ->pluck('id')
                ->filter(fn ($id) => isset($visibleQuestionIdLookup[$id]))
                ->values()
                ->all();

            // Skip nodes that have no visible questions for this audience/context.
            if ($questionIds === []) {
                continue;
            }

            // If any visible question in this node is unanswered, resume here.
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

    protected function paginatedQuestions(): LengthAwarePaginator
    {
        $questions = $this->nodeQuestions()->values();

        $currentPage = $this->getPage($this->pageName);

        $items = $questions
            ->forPage($currentPage, $this->perPage)
            ->values();

        return new LengthAwarePaginator(
            $items,
            $questions->count(),
            $this->perPage,
            $currentPage,
            [
                'path' => request()->url(),
                'pageName' => $this->pageName,
            ]
        );
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

    protected function nodeHasVisibleQuestions(Node $node): bool
    {
        $assessment = $this->assessment();

        if (! $assessment) {
            return false;
        }

        $resolvedTexts = $this->resolvedQuestionTextMap();

        $questionIds = $node->questions->pluck('id');

        return $questionIds->contains(fn ($id) => array_key_exists($id, $resolvedTexts));
    }
    protected function resolvedQuestionTextMap(): array
    {
        if ($this->resolvedQuestionTexts !== []) {
            return $this->resolvedQuestionTexts;
        }

        $assessment = $this->assessment();

        if (! $assessment instanceof Assessment) {
            return [];
        }

        return QuestionTextResolver::optionsFor(
            $assessment,
            $this->assessmentRater()
        );
    }
    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.questions', [
            'questions' => $this->paginatedQuestions(),
        ]);
    }
}
