<?php

namespace App\Livewire;

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
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Questions extends Component
{
    use FormFieldValidationRulesTrait;
    use WithPagination;
    use WithoutUrlPagination;
    use UserTrait;

    public $assessmentId;

    protected $perPage = 3;
    protected $pageName = 'questionId';
    protected $parentPageName = 'assessmentId';

    public ?array $data;
    public ?int $nodeId;

    public function mount(): void
    {
        /**
         * Pre-populate forms with defaults
         */
        $this->data = $this->responses()->toArray();

        if (empty($this->data) && $this->nodeQuestions()) {
            foreach ($this->nodeQuestions() as $question) {
                $defaults = unserialize($question['defaults']) ?? null;
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
        return $this->user->assessments()->where('id', $this->assessmentId)->first()
            ->responses->pluck('scale_option_id', 'question.name');
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
        return AssessmentRater::where('assessment_id', $this->assessmentId)
            ->first();
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
    }

    /**
     * Validate and save user responses
     */
    private function validateAndSaveResponses(): void
    {
        $rules = $this->getRules();
        if (!empty($rules)) {
            $this->validate($rules);
        }

        $questions = $this->nodeQuestions()?->keyBy('name');
        foreach ($this->data as $name => $values) {
            if (isset($questions[$name])) {
                UserResponseService::updateOrCreate(
                    $values,
                    $questions[$name],
                    $this->assessmentId,
                    $this->rater()?->rater_id
                );
            }
        }
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

        $this->dispatch('questions-previous-node', $this->node()?->id);
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
