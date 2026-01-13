<div class="nhsuk-grid-row nhsuk-u-margin-bottom-5">
    <div class="nhsuk-grid-column-full">

        @include('livewire.alerts')

        @if (!empty($this->framework))
            <h1 class="nhsuk-heading-xl">
                {{ $this->framework->name ?? '' }}
            </h1>
            <h2 class="nhsuk-heading-l">Assessment summary</h2>
        @endif

        @foreach ($this->nodes as $node)

            {{-- Top-level node --}}
            @if (empty($node->parent))
                <h3 class="nhsuk-heading-m nhsuk-tag--no-border nhsuk-tag--{{ $node->colour ?? 'blue' }} nhsuk-u-padding-2">
                    {{ config('app.show_node_type_prefix') && $node?->type?->name ? $node->type->name . ': ' : '' }}
                    {{ $node->name }}
                </h3>
            @endif

            {{-- Node with children --}}
            @if ($node->children->count())
                <h4 class="nhsuk-heading-s">
                    {{ config('app.show_node_type_prefix') && $node?->type?->name ? $node->type->name . ': ' : '' }}
                    {{ $node->name }}
                </h4>
            @endif

            {{-- Compute responses ONCE --}}
            @php
                $nodeResponses = $this->responses
                    ->filter(fn ($r) => $r->question?->node_id == $node->id);
            @endphp

            {{-- Leaf node responses --}}
            @if ($nodeResponses->count())
                <ul class="nhsuk-task-list nhsuk-list--border">
                    @foreach ($nodeResponses as $response)
                        <li class="nhsuk-task-list__item nhsuk-task-list__item--with-link nhsuk-u-padding-left-2">

                            <div class="nhsuk-task-list__name-and-hint nhsuk-u-width-three-quarters">
                                <strong>{!! $response->question->title !!}</strong>
                                <br>
                                {!! \App\Services\QuestionTextResolver::textFor(
                                    $this->assessment(),
                                    $this->rater(),
                                    $response->question->id
                                ) !!}

                                @if ($response->question?->response_type === \App\Enums\ResponseType::TYPE_TEXTAREA->value)
                                    <div class="nhsuk-task-list__hint">
                                        {{ $response->textarea }}
                                    </div>
                                @endif
                            </div>

                            <div class="nhsuk-task-list__status">
                                @if ($response->question?->response_type === \App\Enums\ResponseType::TYPE_SCALE->value)
                                    <strong class="nhsuk-tag nhsuk-tag--blue">
                                        {{ Str::match('/^([A-Z][A-Za-z ]+)/', trim($response->scaleOption->label)) }}
                                    </strong>
                                @endif
                            </div>

                        </li>
                    @endforeach
                </ul>
            @endif

        @endforeach

        @if (empty($this->assessment->submitted_at)
            && ($this->responses?->count() !== $this->assessment?->framework?->questions()->where('required', 1)->count()))
            <button class="nhsuk-button"
                    wire:click.prevent="continueAssessment()">
                Continue assessment
            </button>
        @elseif (empty($this->assessment->submitted_at))
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
