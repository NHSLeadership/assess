<div class="nhsuk-grid-row nhsuk-u-margin-bottom-5">
    <div class="nhsuk-grid-column-full">

        @if (!empty($this->framework))

            <h1 class="nhsuk-heading-xl">{{ $this->framework->name }}</h1>

            <p>{{ $this->framework->description }}</p>

            <livewire:alerts />

            @if (!empty($this->frameworks()->variantAttributes))
                <form wire:submit="store()">
                    @foreach ($this->frameworks()->variantAttributes as $variant)
                        @component('components.form.radio', [
                            'name' => 'data.' . $variant->key ?? null,
                            'class' => $variant->class ?? null,
                            'options_list' => $variant->options->pluck('label', 'id')?->toArray() ?? [],
                            'type' => $variant->type ?? null,
                        ])
                            @slot('hint')
                            @endslot
                            @slot('label')
                                <span class="nhsuk-u-visually-hidden">Variant {{$variant->id}}</span>{{ $variant->label ?? null }}
                            @endslot
                        @endcomponent
                        <hr>
                    @endforeach

                        <button class="nhsuk-button" type="submit">Continue</button>
                        <a class="nhsuk-button" href="{{ route('frameworks') }}">Close</a>

                </form>
            @else
                <p>No stages found in the selected framework.</p>
            @endif

        @endif

        <a class="nhsuk-back-link" href="{{ route('frameworks') }}">
            Back
        </a>

    </div>

</div>

