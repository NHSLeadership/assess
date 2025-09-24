<div class="nhsuk-grid-row nhsuk-u-margin-bottom-5">
    <div class="nhsuk-grid-column-full">
        <h1>{{ __('pages.competencies.title') }}</h1>

        <form wire:submit.prevent="store">

            @foreach ( $this->components as $areaName => $components )
                <h2>Area: {{ $areaName }}</h2>

                @foreach ( $components as $component )
                    {{-- Render each component based on eleemnt and it's properties --}}
                    @component('components.form.' . $component['element'], [
                        'name' => $component['name'] ?? null,
                        'class' => $component['class'] ?? null,
                        'id' => $component['id'] ?? $component['name'],
                        'options_list' => $component['options'] ?? null,
                        'type' => $component['type'] ?? null,
                        'value' => $component['value'] ?? null,
                    ])
                        @slot('hint')
                            {{ $component['hint'] ?? null }}
                        @endslot
                        @slot('label')
                            {{ $component['label'] ?? null }}
                        @endslot
                    @endcomponent
                    <hr />
                @endforeach
            @endforeach

{{--            {{ $this->form }}--}}

            <button class="nhsuk-button" type="button">Submit</button>
        </form>
    </div>
</div>
