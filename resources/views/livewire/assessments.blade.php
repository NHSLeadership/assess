<div class="nhsuk-grid-row">
    <div class="nhsuk-grid-column-full">

        @if ($this->nodes?->count())

            @foreach ($paginatedNodes as $node)

                @if(!empty($this->headingHierarchy()))
                    @foreach ($this->headingHierarchy() as $item)
                        <div>
                            <{{ $item['headingTag'] }} class="{{ $item['headingClass'] }} nhsuk-u-padding-2 nhsuk-u-display-inline-block nhsuk-u-margin-top-0 nhsuk-u-margin-bottom-0" style="background-color: {{ \App\Enums\NodeColour::from($item['colour'])?->hex() ?? 'red' }};">
                            @if(!empty($item['name']))
                                    {{ config('app.show_node_type_prefix') && !empty($item['type']) ? $item['type'] . ': ' : '' }}
                                    {{ $item['name'] }}
                            @endif
                        </{{ $item['headingTag'] }}>
                    </div>
                    @endforeach
                @else
                    @if(!empty($node?->parent?->parent?->name))
                        <div>
                            <h1 class="nhsuk-heading-l nhsuk-u-padding-2 nhsuk-u-display-inline-block nhsuk-u-margin-top-0 nhsuk-u-margin-bottom-0" style="background-color: {{ \App\Enums\NodeColour::from($node->colour)?->hex() ?? 'red' }};" >
                                {{ config('app.show_node_type_prefix') && !empty($node?->parent?->parent?->type?->name)
                                    ? $node->parent->parent->type->name . ': '
                                    : '' }}
                                {{ $node->parent->parent->name }}
                            </h1>
                        </div>
                   @endif
                   @if(!empty($node?->parent?->name))
                        <div>
                            <h2 class="nhsuk-heading-m nhsuk-u-padding-2 nhsuk-u-display-inline-block nhsuk-u-margin-top-0 nhsuk-u-margin-bottom-0" style="background-color: {{ \App\Enums\NodeColour::from($node->colour)?->hex() ?? 'red' }};">
                                {{ config('app.show_node_type_prefix') && !empty($node?->parent?->type?->name)
                                    ? $node->parent->type->name . ': '
                                    : '' }}
                                {{ $node->parent->name }}
                            </h2>
                        </div>
                    @endif


                    @if(!empty($node?->name))
                        <div>
                            <h3 class="nhsuk-heading-s nhsuk-u-padding-2 nhsuk-u-display-inline-block nhsuk-u-margin-top-0 nhsuk-u-margin-bottom-0" style="background-color: {{ \App\Enums\NodeColour::from($node->colour)?->hex() ?? 'red' }};">
                                {{ config('app.show_node_type_prefix') && !empty($node?->type?->name)
                                    ? $node->type->name . ': '
                                    : '' }}
                                {{ $node->name }}
                            </h3>
                        </div>
                    @endif

                @endif

                @isset($currentNode->description)
                    <p>{!! $currentNode->description !!}</p>
                @endisset

                <livewire:questions
                        :assessmentId="$this->assessmentId"
                        :nodeId="$this->nodeId"
                        :raterId="$this->raterId"
                        :action="$this->action ?? null"
                        :resolvedQuestionTexts="$this->resolvedQuestionTexts"
                        :wire:key="'questions-assessment-' . $this->assessmentId . '-rater-' . ($this->raterId ?? 'self')"
                />

            @endforeach

            <hr class="nhsuk-u-margin-top-1">

        @else
            <p>
                {{
                    $this->assessment()?->framework
                        ? __('Thank you for your interest. No questions have been set up for this assessment within the selected framework.')
                        : __('Assessment not found or has been removed.')
                }}
            </p>
        @endif

        <a class="nhsuk-back-link" href="{{ route('home') }}">
            {{ __('Home') }}
        </a>

    </div>
</div>
