<?php

namespace App\Livewire;

use App\Models\Form;
use App\Models\User;
use App\Services\UserDataEntry;
use App\Traits\CompetenciesTrait;
use App\Traits\FormFieldValidationRulesTrait;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Illuminate\Support\Collection;
use Livewire\WithPagination;

class FormView extends Component
{
    use FormFieldValidationRulesTrait;
    use CompetenciesTrait;
    use WithPagination;

    public $formId;
    public $perPage = 1;

    public ?array $data = [];

    public function mount(): void
    {
        /**
         * Pre-populate forms with defaults
         */
        if (empty($this->data)) {
            foreach ($this->formFields as $formField) {
                $this->data[$formField['name']] = array_keys($formField['defaults'] ?? []);
            }
        }
        //$this->createYml();
    }

    public function rules(): array
    {
        foreach ($this->formFields as $field) {
            $rules['data.' . $field['name']] = $this->getRulesForType($field);
        }

        return $rules ?? [];
    }

    #[Computed]
    public function forms(): ?Collection
    {
        return Form::all();
    }

    #[Computed]
    public function form(): ?Form
    {
        if (empty($this->formId)) {
            return null;
        }

        return Form::find($this->formId);
    }

    #[Computed]
    public function formFields()
    {
        //$areaId = $this->formGroups[$this->areaId]['id'] ?? 1;
        //if ($this->areas->count() > 1) {
        //return $form->formFields()->where('group_id', $form->category_id)->get();
        //}

        return $this->form->fields()->paginate($this->perPage); //$form->fieldsPerPage ?? 1);
    }

    #[Computed]
    public function user(): ?User
    {
        $user = new User([
            'name' => 'Marcin Calka',
            'email' => 'marcin.calka@nhs.net',
        ]);
        $user->id = 1;

        return $user;
    }

    public function backPage()
    {
        $this->previousPage();
    }

    public function store()
    {
        if ($this->rules()) {
            $this->validate();
        }

        $formFields = $this->formFields?->keyBy('name');

        $user = $this->user();
        foreach ($this->data as $name => $values) {
            if (isset($formFields[$name])) {
                UserDataEntry::updateOrCreate($values, $formFields[$name], $user);
            }
        }

//        $output = [];
//        foreach ($this->formFields as $formField) {
//            $userDataOptions = UserDataOption::where('form_field_id', $formField->id)->with('formFieldOption')->get();
//            foreach ($userDataOptions as $userDataOption) {
//                $output[$this->form->name][$formField['label']][] = [
//                    $userDataOption['form_field_option_id'] => $userDataOption->formFieldOption['value']
//                ];
//            }
//        }

        if ($this->formFields->currentPage() < $this->formFields->lastPage()) {
            $this->nextPage();
        } else {
            $this->redirect(route('assessments', ['assessmentId' => $this->form->assessment_id]));
        }
    }

    public function render()
    {
        return view('livewire.form-view', [
            'formFields' => $this->formFields(),
        ]);
    }
}
