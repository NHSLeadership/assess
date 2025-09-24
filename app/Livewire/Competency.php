<?php

namespace App\Livewire;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Symfony\Component\Yaml\Yaml;

class Competency extends Component implements HasSchemas
{
    use InteractsWithSchemas;

    public ?array $data = [];
    public ?array $components = [];

    public function mount(): void
    {
        $options = [
            1 => 'Strongly disagree',
            2 => 'Disagree',
            3 => 'Neutral',
            4 => 'Agree',
            5 => 'Strongly agree',
        ];

        $this->components = [
            'Competency1' => [
//                [
//                    'element' => 'input',
//                    'type' => 'text'
//                    'name' => 'first_name',
//                    'label' => 'First name',
//                    'hint' => null,
//                    'minLength' => null,
//                    'maxLength' => 255,
//                    'required' => true,
//                ],
//                [
//                    'element' => 'input',
//                    'type' => 'text'
//                    'name' => 'last_name',
//                    'label' => 'Last name',
//                    'hint' => null,
//                    'minLength' => null,
//                    'maxLength' => 255,
//                    'required' => true,
//                ],
//                [
//                    'element' => 'checkbox',
//                    'name' => 'terms',
//                    'label' => 'Terms and conditions',
//                    'hint' => null,
//                    'minLength' => null,
//                    'maxLength' => null,
//                    'required' => true,
//                ],
                [
                    'element' => 'radio',
                    'name' => 'management',
                    'default' => [1],
                    'label' => 'Management is easy',
                    'options' => $options,
                    'hint' => null,
                    'minLength' => null,
                    'maxLength' => null,
                    'required' => true,
                ],
                [
                    'element' => 'checkbox',
                    'name' => 'leadership',
                    'default' => [2],
                    'label' => 'Leadership is easy',
                    'options' => $options,
                    'hint' => null,
                    'minLength' => null,
                    'maxLength' => null,
                    'required' => true,
                ],
                [
                    'element' => 'dropdown',
                    'name' => 'excellence',
                    'default' => [4],
                    'label' => 'Excellence is easy',
                    'options' => $options,
                    'hint' => null,
                    'minLength' => null,
                    'maxLength' => null,
                    'required' => true,
                ],
            ],
        ];
        //dd( Yaml::dump($this->components) );

        $this->form->fill();
    }

    #[Computed]
    public function components() {
        return $this->components;

//        return Yaml::parse(
//            Storage::disk('local')->get('competencies.yml')
//        );
    }

    protected function form(Schema $schema): Schema
    {
        $components = [];
//        $components = Yaml::parse(
//            Storage::disk('local')->get('competencies.yml')
//        );

        foreach ($components as $areaName => $competencies) {
            foreach ($competencies as $competency) {
                $componentsArray[] = match ($competency['element']) {
                    'input' => TextInput::make($competency['name'])
                                            ->label($competency['label'] ?? $competency['name'])
                                            ->maxLength($competency['maxLength'] ?? null)
                                            ->extraInputAttributes(['class' => 'nhsuk-input'])
                                            ->default($competency['default'] ?? null)
                                            //->fieldWrapperView('forms.components.field-wrapper')
                                            ->required($competency['required'] ?? false),

                    'checkbox' => CheckboxList::make($competency['name'])
                                                  ->label($competency['label'] ?? $competency['name'])
                                                  ->options($competency['options'] ?? [1 => $competency['name']])
                                                  ->default($competency['default'] ?? null)
                                                  ->extraFieldWrapperAttributes(['class' => 'nhsuk-form-group'])
                                                  ->extraAttributes(['class' => 'nhsuk-checkboxes'])
                                                  ->extraInputAttributes(['class' => 'nhsuk-checkboxes__input'])
                                                  //->fieldWrapperView('forms.components.field-wrapper')
                                                  ->required($competency['required'] ?? false),

                    'radio' => Radio::make($competency['name'])
                                          ->label($competency['label'] ?? $competency['name'])
                        //->inlineLabel($competency['label'] ?? $competency['name'])
                        //->extraInputAttributes(['class' => 'nhsuk-checkboxes__input'])
                                            ->hint($competency['label'])
                                            ->options($competency['options'] ?? [1 => $competency['name']])
                                          ->default($competency['default'] ?? null)
                                          ->extraFieldWrapperAttributes(['class' => 'nhsuk-radios'])
                                          ->required($competency['required'] ?? false),

                    'markdown' => MarkdownEditor::make($competency['name'])
                                                      ->label($competency['label'] ?? $competency['name'])
                                                      ->maxLength($competency['maxLength'] ?? null)
                                                      ->default($competency['default'] ?? null)
                                                      ->extraAttributes(['class' => 'nhsuk-textarea'])
                                                      ->required($competency['required'] ?? false),

                    'textarea' => Textarea::make($competency['name'])
                                          ->label($competency['label'] ?? $competency['name'])
                                          ->maxLength($competency['maxLength'] ?? null)
                                          ->default($competency['default'] ?? null)
                                          ->extraAttributes(['class' => 'nhsuk-textarea'])
                                          ->required($competency['required'] ?? false),

                    default => null,
                };
            }
        }

        return $schema->components($componentsArray ?? []);
    }

    public function store()
    {
        $state = $this->form->getState();
        //dd($state);
    }

    public function render()
    {
        return view('livewire.competency');
    }
}
