<div class="nhsuk-grid-row nhsuk-u-margin-bottom-5">
    <div class="nhsuk-grid-column-full">

        @if (!empty($this->framework))
            <h1 class="nhsuk-heading-xl">
                {{ $this->framework->name ?? '' }}
            </h1>
            <h2 class="nhsuk-heading-l">Assessment summary</h2>
        @endif

        @foreach ($this->nodes as $node)
            @if (empty($node->parent))
                <h3 class="nhsuk-heading-m nhsuk-tag--no-border nhsuk-tag--{{ $node->colour ?? 'blue' }} nhsuk-u-padding-1">
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
                                <p>{{ $response->question->text ?? '' }}</p>
                                <div class="nhsuk-task-list__hint"></div>
                            </div>
                            <div class="nhsuk-task-list__status nhsuk-task-list__status--completed">
                                <strong class="nhsuk-tag nhsuk-tag--transparent nhsuk-tag--no-border">
                                    <p class="nhsuk-tag nhsuk-tag--grey">{{ $response->scaleOption->label ?? '' }}</p>
                                </strong>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif

            @endif
        @endforeach

        <button class="nhsuk-button">Close</button>
    </div>
</div>
