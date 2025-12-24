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
            @if (empty($node->parent))
                <h3 class="nhsuk-heading-m nhsuk-tag--no-border nhsuk-tag--{{ $node->colour ?? 'blue' }} nhsuk-u-padding-2">
                    {{ config('app.show_node_type_prefix') && $node?->type?->name ? $node->type->name . ': ' : '' }} {{ $node->name ?? '' }}
                </h3>
            @elseif ($node->children->count())
                <h4 class="nhsuk-heading-s">
                    {{ config('app.show_node_type_prefix') && $node?->type?->name ? $node->type->name . ': ' : '' }} {{ $node->name ?? '' }}
                </h4>
            @else

            @if ($this->responses?->where('question.node_id', $node->id)->count())
                <ul class="nhsuk-task-list nhsuk-list--border">
                    @foreach ($this->responses?->where('question.node_id', $node->id) as $response)
                        <li class="nhsuk-task-list__item nhsuk-task-list__item--with-link nhsuk-u-padding-left-2">
                            <div class="nhsuk-task-list__name-and-hint nhsuk-u-width-three-quarters">
                                @if(!empty($response?->assessment?->submitted_at))
                                    <strong>{!! $response->question->title ?? '' !!}</strong>
                                    <br>
                                    {!! \App\Services\QuestionTextResolver::textFor($this->assessment(), $this->rater(), $response->question->id) ?? $response->question?->hint !!}
                                @else
                                    <a href="#" wire:click.prevent="editAnswer({{ $response->question?->node?->id ?? '' }})" class="nhsuk-link nhsuk-task-list__link">
                                        <strong>{!! $response->question->title ?? '' !!}</strong>
                                        <br>
                                        {!! \App\Services\QuestionTextResolver::textFor($this->assessment(), $this->rater(), $response->question->id) ?? $response->question?->hint !!}
                                        <span class="nhsuk-u-visually-hidden">Click to edit this answer</span>
                                    </a>
                                @endif
                                @if(!empty($response?->question?->response_type) && ($response?->question?->response_type === \App\Enums\ResponseType::TYPE_TEXTAREA->value))
                                    <div class="nhsuk-task-list__hint">
                                        {{ $response?->textarea ?? '' }}
                                    </div>
                                @endif
                            </div>
                            <div class="nhsuk-task-list__status">
                                @if(!empty($response?->question?->response_type) && ($response?->question?->response_type === \App\Enums\ResponseType::TYPE_SCALE->value))
                                    <strong class="nhsuk-tag nhsuk-tag--blue">
                                        {{ $response->scaleOption->label ?? '' }}
                                    </strong>
                                @else
                                    {{ $response?->textarea ?? '' }}
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif

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
        @endif
    </div>
</div>
