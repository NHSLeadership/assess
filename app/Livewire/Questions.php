<?php

namespace App\Livewire;

use App\Models\Area;
use App\Models\Assessment;
use App\Services\UserDataEntry;
use App\Traits\CompetenciesTrait;
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
    use CompetenciesTrait;
    use WithPagination;
    use WithoutUrlPagination;
    use UserTrait;

    public $assessmentId;

    protected $perPage = 3;
    protected $pageName = 'questionId';
    protected $parentPageName = 'assessmentId';

    public ?array $data;
    public ?int $areaId;

    public function mount(): void
    {
        /**
         * Pre-populate forms with defaults
         */
        $this->data = $this->userData()->toArray();

        if (empty($this->data) && $this->allFields()) {
            foreach ($this->allFields() as $field) {
                $defaults = unserialize($field['defaults']) ?? null;
                $this->data[$field['name']] = $defaults;
            }
        }
    }

    public function allFields()
    {
        return $this->area()?->fields()->get();
    }

    protected function messages()
    {
        foreach ($this->allFields() as $field) {
            $messages['data.' . $field['name'] . '.numeric'] = 'Select one of the following options';
            $messages['data.' . $field['name'] . '.required'] = 'Select one of the following options';
        }

        return $messages ?? [];
    }

    public function area(): ?Area
    {
        return $this->areas->current();
    }

    #[Computed]
    public function areas()
    {
        if (empty($this->assessment)) {
            return null;
        }

        $areas = $this->assessment?->framework?->areas()->whereNotNull('parent_id')->orderBy('parent_id')->orderBy('id')->get();
        $areasIterator = $areas->getIterator();

        if (empty($this->areaId)) {
            $this->areaId = $areasIterator->key() ?? 0;
        }
        $areasIterator->seek($this->areaId);

        return $areasIterator;
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
        foreach ($this->paginatedFields() as $field) {
            $rules['data.' . $field['name']] = $this->getRulesForType($field);
        }

        return $rules ?? [];
    }

    public function store(): void
    {
        $rules = $this->getRules();
        if (!empty($rules)) {
            $this->validate($rules);
        }

        $fields = $this->allFields()?->keyBy('name');
        foreach ($this->data as $name => $values) {
            if (isset($fields[$name])) {
                UserDataEntry::updateOrCreate($values, $fields[$name], $this->assessmentId, $this->user);
            }
        }

        if ($this->paginatedFields()->hasMorePages()) {
            $this->nextPage(pageName: $this->pageName);
        } else {
            $this->resetPage(pageName: $this->pageName);
            $this->areas->next();
            $this->areaId = $this->areas->key();
        }
    }

    protected function paginatedFields(): ?LengthAwarePaginator
    {
        return $this->area()?->fields()?->paginate($this->perPage, pageName: $this->pageName);
    }

    public function render()
    {
        return view('livewire.questions', [
            'fields' => $this->paginatedFields(),
        ]);
    }
}
