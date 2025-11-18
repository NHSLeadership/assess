<?php

namespace App\Livewire;

use App\Models\Node;
use App\Models\Assessment;
use App\Services\UserDataEntry;
use App\Traits\FormFieldValidationRulesTrait;
use App\Traits\UserTrait;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
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
        $this->data = $this->userData()->toArray();

        if (empty($this->data) && $this->allQuestions()) {
            foreach ($this->allQuestions() as $question) {
                $defaults = unserialize($question['defaults']) ?? null;
                $this->data[$question['name']] = $defaults;
            }
        }
    }

    public function allQuestions(): Collection
    {
        return $this->node()?->questions()->get();
    }

    protected function messages(): array
    {
        foreach ($this->allQuestions() as $question) {
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
                                              ->orderBy('parent_id')->orderBy('order')->orderBy('id')
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
    public function userData(): Collection
    {
        return $this->user->assessments()->where('id', $this->assessmentId)->first()
            ->userDataOptions->pluck('form_field_option_id', 'formField.name');
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

    public function store(): void
    {
        $rules = $this->getRules();
        if (!empty($rules)) {
            $this->validate($rules);
        }

        $questions = $this->allQuestions()?->keyBy('name');
        foreach ($this->data as $name => $values) {
            if (isset($questions[$name])) {
                UserDataEntry::updateOrCreate($values, $questions[$name], $this->assessmentId, $this->user);
            }
        }

        if ($this->paginatedQuestions()->hasMorePages()) {
            $this->nextPage(pageName: $this->pageName);
        } else {
            //TODO: Check if all questions are answered and go to assessment summary (to be done)
            $this->resetPage(pageName: $this->pageName);
            $this->nodes->next();
            $this->nodeId = $this->nodes->key();
        }
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
