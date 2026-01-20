<div class="nhsuk-grid-row nhsuk-u-margin-bottom-5">
    <div class="nhsuk-grid-column-full">

        @if (!empty($this->framework))

            <h1 class="nhsuk-heading-xl">{{ $this->framework->name }}</h1>

            @include('livewire.alerts')

            @if (!empty($this->frameworks()->variantAttributes))
                <form wire:submit="store()">
                    @foreach ($this->frameworks()->variantAttributes as $variant)
                        @component('components.form.radio', [
                            'name' => 'data.' . $variant->key ?? null,
                            'class' => $variant->class ?? null,
                            'options_list' => $variant->options->pluck('label', 'id')?->toArray() ?? [],
                            'type' => $variant->type ?? null,
                            'description' => $variant->hint_text ?? null,
                        ])
                            @slot('label')
                                <span class="nhsuk-u-visually-hidden">Variant {{$variant->id}}</span>{{ $variant->label ?? null }}
                            @endslot
                        @endcomponent
                        <hr>
                    @endforeach

                        <button wire:click.prevent="goPrevious" class="nhsuk-button nhsuk-u-margin-right-3">Previous page</button>
                        <button class="nhsuk-button" type="submit">Save and continue</button>
                        @if ($this->assessment?->framework?->questions?->where('required', 1)->count() && ($this->assessment?->responses?->count() === $this->assessment?->framework?->questions?->where('required', 1)->count()))
                            <a class="nhsuk-button" href="{{ route('summary', ['frameworkId' => $this->frameworkId, 'assessmentId' => $this->assessmentId]) }}" >Finish assessment</a>
                            <div class="nhsuk-inset-text">
                                <span class="nhsuk-u-visually-hidden">Information: </span>
                                <p>You completed all required fields, you can still navigate and change your answers or finish the assessment to receive a report.</p>
                            </div>
                        @endif

                </form>
            @else
                <p>No stages found in the selected framework.</p>
            @endif

        @endif

    </div>

</div>

