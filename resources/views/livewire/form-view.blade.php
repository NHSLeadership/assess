<div class="nhsuk-grid-row nhsuk-u-margin-bottom-5">
    <div class="nhsuk-grid-column-full">
        <h1>{{ $this->form->name ?? '' }}</h1>

        @if (!empty($formFields))
            <form wire:submit.prevent="store()">
                @foreach ($formFields as $field)
                    {{-- Render each component based on type and it's properties --}}
                    @component('components.form.' . $field['element'], [
                        'name' => 'data.' . $field['name'] ?? null,
                        'class' => $field['class'] ?? null,
                        'options_list' => $field->formFieldOptions->pluck('value', 'id')?->toArray() ?? [],
                        'type' => $field['type'] ?? null,
                    ])
                        @slot('hint')
                            {{ $field['hint'] ?? null }}
                        @endslot
                        @slot('label')
                            <span class="nhsuk-u-visually-hidden">Competency {{$field->id}}</span>{{ $field['label'] ?? null }}
                        @endslot
                    @endcomponent
                @endforeach

                <button class="nhsuk-button" type="submit">Continue</button>

                @if (!empty($formFields->previousPageUrl()))
                    <a class="nhsuk-back-link" wire:click.prevent="backPage()" href="#">Step back</a>
                @endif

                {{--{{ $this->formFields->links() ?? '' }}--}}

            </form>
        @else
            <p>No form available</p>
        @endif

    </div>
</div>
