<?php

namespace App\Livewire;

use App\Enums\ResponseType;
use App\Models\Node;
use App\Models\Rater;
use App\Models\Response;
use App\Services\UserResponseService;
use App\Traits\AssessmentHelperTrait;
use App\Traits\FormFieldValidationRulesTrait;
use App\Traits\UserTrait;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;

class Questions extends Component
{
    use FormFieldValidationRulesTrait;
    use WithPagination;
    use WithoutUrlPagination;
    use UserTrait;
    use AssessmentHelperTrait;

    public int $assessmentId;
    public ?int $nodeId = null;
    public ?string $edit = null;

    protected int $perPage = 3;
    protected string $pageName = 'questionId';

    public ?array $data = [];

    public function mount(): void
    {
        // Redirect if not permitted to do an assessment for this framework
        $this->redirectIfAssessmentNotPermitted($this->assessment()?->framework?->id, $this->assessmentId);

        // Redirect submitted/finished assessments to summary page
        $this->redirectIfSubmittedOrFinished($this->assessment(), $this->assessment()?->framework?->id, $this->edit);

        // Pre-populate form data with existing responses
        $this->data = $this->responses()?->toArray() ?? [];

        // If there are no stored responses yet, initialise defaults for visible questions
        if (empty($this->data) && $this->nodeQuestions()->count()) {
            foreach ($this->nodeQuestions() as $question) {
                $defaults = null;

                if ($question->response_type !== ResponseType::TYPE_TEXTAREA->value) {
                    $defaults = unserialize($question->defaults) ?? null;
                }

                $this->data[$question->name] = $defaults;
            }
            $this->resumeToFirstUnansweredQuestion();
        }
    }

    protected function resumeToFirstUnansweredQuestion(): void
    {
        $questions = $this->nodeQuestions();

        if ($questions->isEmpty()) {
            return;
        }

        $firstUnansweredIndex = null;

        foreach ($questions as $i => $question) {
            $key = $question->name;

            $value = $this->data[$key] ?? null;

            $answered = match ($question->response_type) {
                \App\Enums\ResponseType::TYPE_TEXTAREA->value =>
                    is_string($value) && trim($value) !== '',

                default =>
                    !is_null($value) && $value !== '',
            };

            if (! $answered) {
                $firstUnansweredIndex = $i;
                break;
            }
        }

        if (is_null($firstUnansweredIndex)) {
            $this->resetPage(pageName: $this->pageName);
            return;
        }

        $targetPage = intdiv($firstUnansweredIndex, $this->perPage) + 1;

        $this->gotoPage($targetPage, $this->pageName);
    }

    #[Computed]
    public function assessmentIsComplete(): bool
    {
        $requiredTotal = $this->assessment()?->framework
            ?->questions()
            ->where('required', 1)
            ->count() ?? 0;

        $requiredAnswered = $this->requiredResponses()?->count() ?? 0;

        return $requiredTotal > 0 && $requiredAnswered === $requiredTotal;
    }

    #[Computed]
    public function node(): ?Node
    {
        if (! $this->nodeId) {
            return null;
        }

        return Node::query()
            ->with([
                'questions' => fn ($q) => $q->where('active', true)->orderBy('order'),
            ])
            ->find($this->nodeId);
    }

    public function nodeQuestions(): Collection
    {
        return $this->node?->questions?->values() ?? collect();
    }

    protected function messages(): array
    {
        $messages = [];

        foreach ($this->nodeQuestions() as $question) {
            $messages['data.' . $question->name . '.numeric'] = 'Select one of the following options';

            if ($question->response_type !== ResponseType::TYPE_TEXTAREA->value) {
                $messages['data.' . $question->name . '.required'] = 'Select one of the following options';
            } else {
                $messages['data.' . $question->name . '.required'] = 'Enter your response';
                $messages['data.' . $question->name . '.max'] =
                    'Your response must be less than ' .
                    $this->getMaxLengthForType(ResponseType::TYPE_TEXTAREA->value) .
                    ' characters';
            }
        }

        return $messages;
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
        $assessment = $this->user->assessments()
            ->where('id', $this->assessmentId)
            ->with('responses.question')
            ->first();

        if (! $assessment) {
            return null;
        }

        $responses = $assessment->responses;

        if ($onlyRequired) {
            $responses = $responses->filter(fn ($response) => (bool) ($response->question->required ?? false));
        }

        return $responses->mapWithKeys(function ($response) use ($onlyRequired) {
            $key = $response->question->name;

            if ($response->question->response_type === ResponseType::TYPE_TEXTAREA->value) {
                return [$key => $response->textarea ?? ''];
            }

            if ($onlyRequired) {
                return [$key => $response->scale_option_id];
            }

            return [
                $key => $response->scale_option_id,
                $key . '_reflection' => $response->textarea ?? '',
            ];
        });
    }

    protected function paginatedQuestions(): ?LengthAwarePaginator
    {
        if (! $this->node) {
            return null;
        }

        return $this->node
            ->questions()
            ->where('active', true)
            ->orderBy('order')
            ->paginate($this->perPage, pageName: $this->pageName);
    }

    public function getRules(): array
    {
        $rules = [];

        $paginator = $this->paginatedQuestions();
        if (! $paginator) {
            return $rules;
        }

        foreach ($paginator as $question) {
            $rules['data.' . $question->name] = $this->getRulesForType($question);
        }

        return $rules;
    }

    public function rater(): ?Rater
    {
        return $this->assessment()?->raters()?->first();
    }

    public function storeNext(): void
    {
        $this->validateAndSaveResponses();

        $paginator = $this->paginatedQuestions();

        if ($paginator && $paginator->hasMorePages()) {
            $this->nextPage(pageName: $this->pageName);
            $this->dispatch('scroll-to-top');
            return;
        }

        if ($this->assessmentIsComplete) {
            $this->dispatch('scroll-to-top');
            return;
        }

        $this->resetPage(pageName: $this->pageName);
        $this->dispatch('assessment-next-node');
        $this->dispatch('scroll-to-top');
    }

    public function goPrevious(): void
    {
        if ($this->getPage($this->pageName) > 1) {
            $this->previousPage(pageName: $this->pageName);
            $this->dispatch('scroll-to-top');
            return;
        }

        $this->dispatch('assessment-prev-node');
        $this->dispatch('scroll-to-top');
    }

    private function validateAndSaveResponses(): void
    {
        $rules = $this->getRules();

        if (! empty($rules)) {
            try {
                $this->validate($rules);
            } catch (ValidationException $e) {
                $this->dispatch('scroll-to-top');
                throw $e;
            }
        }

        $questions = $this->nodeQuestions()->keyBy('name');

        foreach ($this->data as $name => $value) {

            if (str_ends_with($name, '_reflection')) {
                continue;
            }

            if (! isset($questions[$name])) {
                continue;
            }

            $question = $questions[$name];

            if (
                $question->response_type === ResponseType::TYPE_TEXTAREA->value &&
                empty(trim((string) $value))
            ) {
                continue;
            }

            UserResponseService::updateOrCreate(
                $value,
                $question,
                $this->assessmentId,
                $this->rater()?->id
            );

            if ($question->response_type === ResponseType::TYPE_SCALE->value) {
                $reflectionKey = $name . '_reflection';
                $reflection = trim((string) ($this->data[$reflectionKey] ?? ''));

                Response::updateOrCreate(
                    [
                        'assessment_id' => $this->assessmentId,
                        'question_id'   => $question->id,
                        'rater_id'      => $this->rater()?->id,
                    ],
                    [
                        'textarea'   => $reflection,
                    ]
                );
            }
        }
    }

    #[Computed]
    public function orderedQuestionIds(): Collection
    {
        $frameworkId = $this->assessment()?->framework_id;
        if (! $frameworkId) {
            return collect();
        }

        $roots = Node::query()
            ->where('framework_id', $frameworkId)
            ->whereNull('parent_id')
            ->orderBy('order')
            ->orderBy('id')
            ->with([
                'questions' => fn ($q) => $q->where('active', true)->orderBy('order'),
                'children' => fn ($q) => $q->orderBy('order')->orderBy('id')->with([
                    'questions' => fn ($q) => $q->where('active', true)->orderBy('order'),
                    'children' => fn ($q) => $q->orderBy('order')->orderBy('id')->with([
                        'questions' => fn ($q) => $q->where('active', true)->orderBy('order'),
                    ]),
                ]),
            ])
            ->get();

        $ids = collect();

        $walk = function (Collection $nodes) use (&$walk, &$ids) {
            foreach ($nodes as $node) {
                foreach ($node->questions as $q) {
                    $ids->push($q->id);
                }

                if ($node->relationLoaded('children') && $node->children->isNotEmpty()) {
                    $walk($node->children);
                }
            }
        };

        $walk($roots);

        return $ids;
    }

    public function getQuestionProgressLabel(?int $questionId = null): string
    {
        if (! $questionId) {
            return '';
        }

        $ids = $this->orderedQuestionIds;
        $index = $ids->search($questionId);

        if ($index === false) {
            return '';
        }

        $currentNumber = $index + 1;
        $total = $ids->count();

        return "<strong>Response {$currentNumber} of {$total}</strong>";
    }

    public function finishAssessment()
    {
        $this->validateAndSaveResponses();

        return redirect()->route('summary', [
            'frameworkId'  => $this->assessment()?->framework?->id,
            'assessmentId' => $this->assessmentId,
        ]);
    }

    public function goToVariantSelection()
    {
        return redirect()->route('variants', [
            'frameworkId'  => $this->assessment()?->framework?->id,
            'assessmentId' => $this->assessmentId,
            'back'         => 1,
        ]);
    }

    public function buildScaleOptions($question): array
    {
        if (! $question?->scale) {
            return [];
        }

        return $question->scale->options()
            ->orderBy('order')
            ->get()
            ->mapWithKeys(function ($opt) {
                $label = $opt->label;

                if (! empty($opt->description)) {
                    $label .= ' - ' . $opt->description;
                }

                return [$opt->id => $label];
            })
            ->toArray();
    }

    public function render()
    {
        return view('livewire.questions', [
            'questions' => $this->paginatedQuestions(),
        ]);
    }
}
