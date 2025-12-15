<div class="nhsuk-grid-row nhsuk-u-margin-bottom-5">
    <div class="nhsuk-grid-column-full">

        <livewire:alerts />

        @if (!empty($this->framework))
            <h1 class="nhsuk-heading-xl">
                {{ $this->framework->name ?? '' }}
            </h1>
            <h2 class="nhsuk-heading-l">Assessment summary</h2>
        @endif

        @foreach ($this->nodes as $node)
            @if (empty($node->parent))
                <h3 class="nhsuk-heading-m nhsuk-tag--no-border nhsuk-tag--{{ $node->colour ?? 'blue' }} nhsuk-u-padding-2">
                    {{ $node->name ?? '' }}
                </h3>
            @elseif ($node->children->count())
                <h4 class="nhsuk-heading-s">
                    {{ $node->name ?? '' }}
                </h4>
            @else

            @if ($this->responses?->where('question.node_id', $node->id)->count())
                <ul class="nhsuk-task-list nhsuk-list--border">
                    @foreach ($this->responses?->where('question.node_id', $node->id) as $response)
                        <li class="nhsuk-task-list__item nhsuk-task-list__item--with-link nhsuk-u-padding-left-2">
                            <div class="nhsuk-task-list__name-and-hint">
                                <a href="#" wire:click.prevent="editAnswer({{ $response->question?->node?->id ?? '' }})" class="nhsuk-link nhsuk-task-list__link">
                                    {!! $response->question->title ?? '' !!}
                                    <span class="nhsuk-u-visually-hidden"> – edit this answer</span>
                                </a>
                            </div>
                            <div class="nhsuk-task-list__status">
                                <strong class="nhsuk-tag nhsuk-tag--blue">
                                    {{ $response->scaleOption->label ?? '' }}
                                </strong>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif

            @endif
        @endforeach

        @if (empty($this->assessment->submitted_at))
            <button class="nhsuk-button"
                    wire:click.prevent="editAnswer({{ $this->nodes?->last()?->id }})">
                Previous question
            </button>
            <button class="nhsuk-button"
                    wire:click.prevent="confirmSubmit()">
                Complete assessment
            </button>
        @endif
    </div>
</div>
