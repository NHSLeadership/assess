<div class="nhsuk-grid-row">
    <div class="nhsuk-grid-column-full">

        @if (!empty($fields))
            <form wire:submit="store()">
                @foreach ($fields as $field)
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

                {{-- Submit button continues to next page instead of pagination links --}}
                <button class="nhsuk-button" type="submit">Continue</button>
            </form>

            @if (!empty($fields->previousPageUrl()))
                <div class="nhsuk-back-link">
                    <a class="nhsuk-back-link__link" wire:click.prevent="backPage()" href="#">
                        <svg class="nhsuk-icon nhsuk-icon__chevron-left" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M8.5 12c0-.3.1-.5.3-.7l5-5c.4-.4 1-.4 1.4 0s.4 1 0 1.4L10.9 12l4.3 4.3c.4.4.4 1 0 1.4s-1 .4-1.4 0l-5-5c-.2-.2-.3-.4-.3-.7z"></path>
                        </svg>{{ __('Previous question') }}</a>
                </div>
            @endif

        @else
            <p>Questions not found.</p>
            <a class="nhsuk-back-link" wire:click.prevent="backPage()" href="{{ route('areas', $this->assessment->framework->id) }}">Step back</a>
        @endif

    </div>
</div>
