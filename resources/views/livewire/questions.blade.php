<div class="nhsuk-grid-row">
    <div class="nhsuk-grid-column-full">
        @if (!empty($questions))
            <form wire:submit.prevent="storeNext()">
                @foreach ($questions as $question)
{{--                    <h2 class="nhsuk-heading-m">--}}
{{--                        <span class="nhsuk-tag--{{ $question->node->colour ?? 'blue' }} nhsuk-tag--no-border nhsuk-u-padding-2">--}}
{{--                          {!! $question->node?->parent?->name ?? '' !!} >  {!! $question->node->name ?? '' !!}--}}
{{--                        </span>--}}
{{--                    </h2>--}}

                    {{-- Render each component based on type and it's properties --}}
                    @component('components.form.' . $question['component'], [
                        'name' => $question['name'] ? 'data.' . $question['name'] : null,
                        'class' => $question['class'] ?? null,
                        'options_list' => $question?->scale?->options()->orderBy('order')->pluck('label', 'id')?->toArray() ?? [],
                        'type' => $question['type'] ?? null,
                        'hints' => [
                            $question['text'] ?? null,
                            \App\Services\QuestionTextResolver::textFor($this->assessment(), $this->rater(), $question['id']) ?? $question['hint']
                         ]

                    ])
                        @slot('label')
                            <span class="nhsuk-u-visually-hidden">Competency {{$question->id}}</span>{!! $this->getQuestionProgressLabel($question['id'] ?? null) . ': '. $question['title'] ?? null !!}
                        @endslot
                    @endcomponent
                    <hr>
                @endforeach

                @if ($this->responses?->count())
                    {{-- Submit button continues to next page instead of pagination links --}}
                    <div>
                        @if($this->nodes()->key() + 1 > 0)
                                <button wire:click.prevent="goPrevious" class="nhsuk-button nhsuk-u-margin-right-3">Previous page</button>
                        @endif
                        @if($this->nodes()->count() > $this->nodes()->key() + 1 )
                            <button wire:submit.prevent="storeNext" class="nhsuk-button nhsuk-u-margin-right-3" type="submit">Save and continue</button>
                        @endif

                        @if ($this->responses?->count() === $this->assessment?->framework?->questions?->where('required', 1)->count() || $this->nodes()->count() === $this->nodes()->key() + 1)
                            <button wire:click.prevent="finishAssessment" class="nhsuk-button nhsuk-u-margin-right-3" >Complete assessment</button>
                            @if ($this->responses?->count() === $this->assessment?->framework?->questions?->where('required', 1)->count())
                                <div class="nhsuk-inset-text">
                                    <span class="nhsuk-u-visually-hidden">Information: </span>
                                    <p>You completed all required fields, you can still navigate and change your answers or finish the assessment to receive a report.</p>
                                </div>
                            @endif
                        @endif
                    </div>
                @else
                    {{-- No responses yet, from the variant select page --}}
                    <button wire:click.prevent="goToVariantSelection" class="nhsuk-button nhsuk-u-margin-right-3">Previous page</button>
                    <button class="nhsuk-button" type="submit">Save and continue</button>
                @endif
            </form>

        @else
            <p>Questions not found.</p>

            <a class="nhsuk-back-link" wire:click.prevent="backPage()" href="{{ route('frameworks', $this->assessment->framework->id) }}">Step back</a>
        @endif

    </div>
</div>
