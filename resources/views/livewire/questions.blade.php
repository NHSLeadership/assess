<div class="nhsuk-grid-row">
    <div class="nhsuk-grid-column-full">

        @if (!empty($questions))
            <form wire:submit="store()">
                @foreach ($questions as $question)
                    {{-- Render each component based on type and it's properties --}}
                    @component('components.form.' . $question['component'], [
                        'name' => 'data.' . $question['name'] ?? null,
                        'class' => $question['class'] ?? null,
                        'options_list' => $question->scale->options()->pluck('label', 'id')?->toArray() ?? [],
                        'type' => $question['type'] ?? null,
                    ])
                        @slot('hint')
                            {{ $question['text'] ?? $question['hint'] }}
                        @endslot
                        @slot('label')
                            <span class="nhsuk-u-visually-hidden">Competency {{$question->id}}</span>{{ $question['title'] ?? null }}
                        @endslot
                    @endcomponent
                    <hr>
                @endforeach

                @if ($this->responses?->count())
                    <div class="nhsuk-inset-text">
                        <span class="nhsuk-u-visually-hidden">Information: </span>
                        <p>You completed all required fields, you can still navigate and change your answers or finish the assessment to receive a report.</p>
                    </div>
                    {{-- Submit button continues to next page instead of pagination links --}}
                    <button class="nhsuk-button nhsuk-u-margin-right-3" type="submit">Continue</button>

                    @if ($this->responses?->count() === $this->assessment?->framework?->questions?->where('required', 1)->count())
                        <a class="nhsuk-button" href="{{ route('summary', ['frameworkId' => $this->assessment?->framework->id, 'assessmentId' => $this->assessmentId]) }}">Finish assessment</a>
                    @endif
                @else
                    {{-- Submit button continues to next page instead of pagination links --}}
                    <button class="nhsuk-button" type="submit">Continue</button>
                @endif
            </form>

            @if (!empty($questions->previousPageUrl()))
                <div class="nhsuk-back-link">
                    <a class="nhsuk-back-link__link" wire:click.prevent="backPage()" href="#">
                        <svg class="nhsuk-icon nhsuk-icon__chevron-left" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M8.5 12c0-.3.1-.5.3-.7l5-5c.4-.4 1-.4 1.4 0s.4 1 0 1.4L10.9 12l4.3 4.3c.4.4.4 1 0 1.4s-1 .4-1.4 0l-5-5c-.2-.2-.3-.4-.3-.7z"></path>
                        </svg>{{ __('Previous question') }}</a>
                </div>
            @endif

        @else
            <p>Questions not found.</p>

            <a class="nhsuk-back-link" wire:click.prevent="backPage()" href="{{ route('frameworks', $this->assessment->framework->id) }}">Step back</a>
        @endif

    </div>
</div>
