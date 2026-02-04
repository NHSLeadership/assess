<div class="nhsuk-grid-row nhsuk-u-margin-bottom-5">
    <div class="nhsuk-grid-column-full">

        @include('livewire.alerts')

        @if (!empty($this->framework))
            <h1 class="nhsuk-heading-l">
                {{ $this->framework->name ?? null }} {{ strtolower(($this->loggedInRater($this->assessment)?->pivot?->assessment_type) ?? 'self assessment') }}
            </h1>
            <h2 class="nhsuk-heading-l">Assessment summary</h2>
            @if(empty($this->assessment?->submitted_at))
                <p>
                   {!! __('pages.summary.response-edit-prompt') !!}
                </p>
            @endif
        @endif

        @foreach ($this->nodes as $node)

            {{-- Top-level node --}}
            @if (empty($node->parent))
                <div>
                    <h3 class="nhsuk-heading-m nhsuk-u-padding-2 nhsuk-u-display-inline-block nhsuk-u-margin-top-0 nhsuk-u-margin-bottom-0" style="background-color: {{ \App\Enums\NodeColour::from($node->colour)?->hex() ?? 'red' }};">
                        {{ config('app.show_node_type_prefix') && $node?->type?->name ? $node->type->name . ': ' : '' }}
                        {{ $node->name }}
                    </h3>
                </div>

                {{-- Node with children --}}
            @elseif ($node->children->count())
                <h4 class="nhsuk-heading-s">
                    {{ config('app.show_node_type_prefix') && $node?->type?->name ? $node->type->name . ': ' : '' }}
                    {{ $node->name }}
                </h4>
            @endif

            {{-- Compute responses ONCE --}}
            @php
                $nodeQuestions = $node->questions; // or however your relationship is named
            @endphp


            {{-- Leaf node responses --}}
            @if ($nodeQuestions->count())
                <ul class="nhsuk-task-list nhsuk-list--border">
                    @foreach ($nodeQuestions as $question)
                        @php
                            $response = $this->responses
                                ->firstWhere('question_id', $question->id);
                        @endphp

                        <li class="nhsuk-task-list__item nhsuk-task-list__item--with-link nhsuk-u-padding-left-2">
                            <div class="nhsuk-task-list__name-and-hint nhsuk-u-width-three-quarters">

                                {{-- Title --}}
                                @if(!empty($this->assessment->submitted_at))
                                    <strong>{!! $question->title !!}</strong>
                                @else
                                    <a href="#" wire:click.prevent="editAnswer({{ $question->node_id }})"
                                       class="nhsuk-link nhsuk-task-list__link">
                                        <strong>{!! $question->title !!}</strong>
                                    </a>
                                    <span class="nhsuk-u-visually-hidden">Click to edit this answer</span>
                                @endif

                                <br>

                                {{-- Question text --}}
                                {!! \App\Services\QuestionTextResolver::textFor(
                                    $this->assessment(),
                                    $this->rater(),
                                    $question->id
                                ) !!}

                                {{-- Response (if exists) --}}
                                @if ($response)
                                    @php $type = $question->response_type; @endphp

                                    @if ($type === \App\Enums\ResponseType::TYPE_TEXTAREA->value)
                                        <div class="nhsuk-task-list__hint">
                                            {{ $response->textarea }}
                                        </div>

                                    @elseif ($type === \App\Enums\ResponseType::TYPE_SCALE->value)
                                        <div class="nhsuk-task-list__hint">
                                            <strong class="nhsuk-tag nhsuk-tag--blue">
                                                {{ $response->scaleOption->label }}
                                                {{ $response->scaleOption->description ? ' - ' . $response->scaleOption->description : '' }}
                                            </strong>
                                        </div>
                                    @endif

                                @else
                                    {{-- No response yet --}}
                                    <div class="nhsuk-task-list__hint nhsuk-u-secondary-text-color">
                                        <em>No answer provided</em>
                                    </div>
                                @endif

                            </div>
                        </li>
                    @endforeach
                </ul>

            @endif
        @endforeach

        @php
            $isSubmitted = (bool) $this->assessment->submitted_at;
            $hasAllRequired = $this->answeredRequiredCount === $this->requiredCount;
        @endphp

        @if (!$isSubmitted && !$hasAllRequired)
            <button class="nhsuk-button" wire:click.prevent="continueAssessment()">
                Continue assessment
            </button>
        @elseif (!$isSubmitted)
            <h2 class="nhsuk-heading-m">Complete your assessment</h2>
            <p>You will not be able to change your responses after completing your assessment</p>
            <button class="nhsuk-button"
                    wire:click.prevent="confirmSubmit()">
                Complete assessment
            </button>
        @else
            <button class="nhsuk-button"
                    wire:click.prevent="viewReport()">
                View report
            </button>
        @endif
    </div>
</div>
