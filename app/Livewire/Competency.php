<?php

namespace App\Livewire;

use App\Models\Form;
use App\Models\UserDataOption;
use App\Services\UserDataEntry;
use App\Traits\CompetenciesTrait;
use App\Traits\FormFieldValidationRulesTrait;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\Yaml\Yaml;

use function Psy\debug;

class Competency extends Component implements HasSchemas
{
    use InteractsWithSchemas;
    use FormFieldValidationRulesTrait;
    use CompetenciesTrait;
    use WithPagination;

    public ?int $formId;
    public ?int $groupId;
    public ?array $data = [];

    public function mount(): void
    {
        $this->formId = 1;

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

    public function rules()
    {
        foreach ($this->formFields as $field) {
            $rules['data.' . $field['name']] = $this->getRulesForType($field);
        }

        return $rules ?? [];
    }

    #[Computed]
    public function formFields()
    {
        $form = Form::find($this->formId);

        if (!empty($this->groupId)) {
            return $form->formFields()->where('group_id', $this->groupId)->get();
        }

        return $form->formFields()->paginate($form->perPage ?? 1);
    }

    public function store()
    {
        $this->validate();
        $formFields = $this->formFields?->keyBy('name');
//        var_export([
//            $this->data,
//            $formFields,
//        ]);

        foreach ($this->data as $name => $values) {
            if (isset($formFields[$name])) {
                UserDataEntry::updateOrCreate($values, $formFields[$name]);
            }
        }

        $output = [];
        $form = Form::find($this->formId);

        foreach ($this->formFields as $formField) {
            $userDataOptions = UserDataOption::where('form_field_id', $formField->id)->with('formFieldOption')->get();
            foreach ($userDataOptions as $userDataOption) {
                $output[$form->name][$formField['label']][] = [
                    $userDataOption['form_field_option_id'] => $userDataOption->formFieldOption['value']
                ];
            }
        }

        $this->groupId++;

        return $this->redirect(route('forms', $this->groupId));
    }

    public function render()
    {
        return view('livewire.competency');
    }
}
