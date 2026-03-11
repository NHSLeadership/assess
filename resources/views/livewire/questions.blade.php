<div class="nhsuk-grid-row">
    <div class="nhsuk-grid-column-full">

        @if (!empty($questions) && $questions->count())

            <form wire:submit.prevent="storeNext">

                @foreach ($questions as $question)
                    <div wire:key="question-{{ $question->id }}">

                        {{-- Render the main question input --}}
                        @component('components.form.' . $question->component, [
                            'name' => $question->name ? 'data.' . $question->name : null,
                            'class' => $question->class ?? null,
                            'options_list' => $this->buildScaleOptions($question),
                            'type' => $question->type ?? null,
                            'descriptions' => [
                                $question->text ?? null,
                                \App\Services\QuestionTextResolver::textFor(
                                    $this->assessment(),
                                    $this->rater(),
                                    $question->id
                                ),
                            ],
                        ])
                            @slot('label')
                                <span class="nhsuk-u-visually-hidden">
                                    Question {{ $question->id }}
                                </span>

                                @php
                                    $title = trim($question->title ?? '');
                                    $nodeTitle = trim($question->node?->name ?? '');
                                    $showTitle = $title !== '' && strcasecmp($title, $nodeTitle) !== 0;
                                @endphp

                                {!! $this->getQuestionProgressLabel($question->id) !!}
                                @if ($showTitle)
                                    : {{ $title }}
                                @endif

                                @if (! $question->required)
                                    <span class="nhsuk-tag">Optional</span>
                                @endif
                            @endslot
                        @endcomponent

                        <hr class="nhsuk-u-margin-top-0">

                        {{-- Optional reflection for scale questions --}}
                        @if ($question->component === \App\Enums\ResponseType::TYPE_SCALE->component())
                            @component('components.form.textarea', [
                                'name' => $question->reflection ? 'data.' . $question->reflection : null,
                                'class' => 'nhsuk-u-margin-bottom-0',
                            ])
                                @slot('label')
                                    <span class="nhsuk-u-visually-hidden">
                                        Reflection {{ $question->id }}
                                    </span>
                                    {!! __('pages.questions.reflection-label') !!}
                                    <span class="nhsuk-tag">Optional</span>
                                @endslot
                            @endcomponent
                        @endif

                        <hr class="nhsuk-u-margin-top-0">
                    </div>
                @endforeach

                {{-- Navigation buttons --}}
                <div class="nhsuk-u-margin-top-3">

                    {{-- Previous page (always smart) --}}
                    <button
                            wire:click.prevent="goPrevious"
                            type="button"
                            class="nhsuk-button nhsuk-button--secondary nhsuk-u-margin-right-3">
                        Previous page
                    </button>

                    {{-- Single primary forward action --}}
                    @if ($this->isLastQuestion)
                        <button
                                wire:click.prevent="storeAndFinish"
                                type="button"
                                class="nhsuk-button nhsuk-u-margin-right-3">
                            View summary
                        </button>
                    @else
                        <button
                                type="submit"
                                class="nhsuk-button nhsuk-u-margin-right-3">
                            Save and continue
                        </button>
                    @endif

                </div>
            </form>

        @else
            <p>Questions not found.</p>

            <a
                    class="nhsuk-back-link"
                    wire:click.prevent="backPage"
                    href="{{ route('frameworks') }}">
                Step back
            </a>
        @endif

    </div>
</div>
