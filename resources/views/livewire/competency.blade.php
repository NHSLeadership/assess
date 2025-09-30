<div class="nhsuk-grid-row nhsuk-u-margin-bottom-5">
    <div class="nhsuk-grid-column-full">
        <h1>{{ __('pages.competencies.title') }}</h1>

        @if (!empty($this->formFields))
            @if ($this->groupId)
            <h3>Group {{ $this->groupId }}</h3>
            @endif

            <form wire:submit.prevent="store()">
                @foreach ( $this->formFields as $field )
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
                            {{ $field['label'] ?? null }}
                        @endslot
                    @endcomponent
                @endforeach

                <button class="nhsuk-button" type="submit">Continue</button>

{{--                {{ $this->formFields->links() ?? '' }}--}}
{{--                    @dd($this->getPage())--}}

            </form>
        @else
            <p>No form available</p>
        @endif
    </div>
</div>
