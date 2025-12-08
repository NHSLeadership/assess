<?php

namespace App\Livewire;

use App\Enums\ResponseType;
use App\Models\AssessmentRater;
use App\Models\Node;
use App\Models\Assessment;
use App\Models\Rater;
use App\Models\Response;
use App\Services\UserResponseService;
use App\Traits\FormFieldValidationRulesTrait;
use App\Traits\UserTrait;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use App\Traits\RedirectSubmittedAssessment;

class Questions extends Component
{
    use FormFieldValidationRulesTrait;
    use WithPagination;
    use WithoutUrlPagination;
    use UserTrait;
    use RedirectSubmittedAssessment;

    public $assessmentId;

    protected $perPage = 3;
    protected $pageName = 'questionId';

    public ?array $data;
    public ?int $nodeId;

    public $selectNode;

    public function mount(): void
    {
        $this->redirectIfSubmitted($this->assessmentId, $this->assessment?->framework->id);

        /**
         * Pre-populate forms with defaults
         */
        $this->data = $this->responses()->toArray();

        if (empty($this->data) && $this->nodeQuestions()) {
            foreach ($this->nodeQuestions() as $question) {
                $defaults = null;
                // This may be a match/switch expression in future based on multiple response_types
                if($question->response_type !== ResponseType::TYPE_TEXTAREA->value){
                    $defaults = unserialize($question['defaults']) ?? null;
                }
                $this->data[$question['name']] = $defaults;
            }
        }
    }

    public function nodeQuestions(): Collection
    {
        return $this->node()?->questions()->get();
    }

    protected function messages(): array
    {
        foreach ($this->nodeQuestions() as $question) {
            $messages['data.' . $question['name'] . '.numeric'] = 'Select one of the following options';
            $messages['data.' . $question['name'] . '.required'] = 'Select one of the following options';
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
        if (empty($this->assessment)) {
            return null;
        }

        /**
         * Take all nodes with questions only
         */
        $nodes = $this->assessment?->framework?->nodes()
                                              ->whereHas('questions')
                                              ->orderBy('order')->orderBy('id')
                                              ->get();
        /**
         * Convert collection to iterator
         */
        $nodesIterator = $nodes->getIterator();
        if (empty($this->nodeId)) {
            $this->nodeId = $nodesIterator->key() ?? 0;
        }
        $nodesIterator->seek($this->nodeId);

        return $nodesIterator;
    }

    #[Computed]
    public function assessment(): Assessment
    {
        return Assessment::find($this->assessmentId);
    }

    #[Computed]
    public function responses(): Collection
    {
        $assessment = $this->user->assessments()
            ->where('id', $this->assessmentId)
            ->with('responses.question')
            ->first();
        return $assessment->responses
            ->mapWithKeys(function ($response) {
                $key = $response->question->name;

                if ($response->question->response_type === ResponseType::TYPE_TEXTAREA->value) {
                    $value = $response->textarea ?? '';
                } else {
                    $value = $response->scale_option_id;
                }

                return [$key => $value];
            });
    }

    public function backPage(): void
    {
        $this->previousPage();
    }

    public function getRules(): array
    {
        $rules = [];
        foreach ($this->paginatedQuestions() as $question) {
            $rules['data.' . $question['name']] = $this->getRulesForType($question);
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
            $this->nodeId = $this->nodes->key();
        }

        $this->dispatch('questions-next-node', $this->node()?->id);
        $this->dispatch('scroll-to-top');
    }

    /**
     * Validate and save user responses
     * @throws ValidationException
     */
    private function validateAndSaveResponses(): void
    {
        $rules = $this->getRules();
        if (!empty($rules)) {
            try {
                $this->validate($rules);
            } catch (\Illuminate\Validation\ValidationException $e) {
                // Dispatch your event only on failure
                $this->dispatch('scroll-to-top');
                // Rethrow - Livewire still shows the error messages
                throw $e;
            }
        }

        $questions = $this->nodeQuestions()?->keyBy('name');
        foreach ($this->data as $name => $values) {
            if (isset($questions[$name])) {
                UserResponseService::updateOrCreate(
                    $values,
                    $questions[$name],
                    $this->assessmentId,
                    $this->rater()?->id
                );
            }
        }
    }

    /**
     * Get question progress label
     */
    public function getQuestionProgressLabel(): string
    {
        $nodes = $this->nodes()->getArrayCopy();

        $currentNodeId = $this->nodeQuestions()->first()?->node_id;

        if (!$currentNodeId) {
            return '';
        }

        $questionCounts = [];
        foreach ($nodes as $node) {
            $questionCounts[$node->id] = $node->questions()->count();
        }
        $questionCounter = 0;
        foreach ($nodes as $node) {
            if ($node->id === $currentNodeId) {
                break;
            }
            $questionCounter += $questionCounts[$node->id];
        }
        $currentOffset = $this->nodeQuestions()->first()?->order ?? 0;

        $currentNumber = $questionCounter + $currentOffset + 1;

        $total = $this->assessment?->framework->questions()->count() ?? 0;

        return "<strong>Question {$currentNumber} of {$total}</strong>";
    }

    /**
     * Go to previous question or node
     */
    public function goPrevious(): void
    {
        $this->resetPage(pageName: $this->pageName);

        if ($this->nodeId > 0) {
            $this->nodes->seek($this->nodeId - 1);
            $this->nodeId = $this->nodes->key();
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
                ['frameworkId' => $this->assessment?->framework->id, 'assessmentId' => $this->assessmentId]
            );
    }


    protected function paginatedQuestions(): ?LengthAwarePaginator
    {
        return $this->node()?->questions()?->paginate($this->perPage, pageName: $this->pageName);
    }

    public function render()
    {
        return view('livewire.questions', [
            'questions' => $this->paginatedQuestions(),
        ]);
    }
}
