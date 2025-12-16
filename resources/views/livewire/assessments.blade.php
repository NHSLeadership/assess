<div class="nhsuk-grid-row">
    <div class="nhsuk-grid-column-full">

{{--        @if (!empty($this->assessment->framework->name))--}}
{{--            <h1 class="nhsuk-heading-xl nhsuk-u-margin-bottom-3">{{ $this->assessment->framework->name }}</h1>--}}
{{--        @endif--}}

        @if (!empty($this->assessment->id))
{{--            <div class="nhsuk-action-link disabled">--}}
{{--                <a class="nhsuk-action-link__link disabled"  href="{{ route('review-request', $this->assessment->id) }}">--}}
{{--                    <svg class="nhsuk-icon nhsuk-icon__arrow-right-circle" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true">--}}
{{--                        <path d="M0 0h24v24H0z" fill="none"></path>--}}
{{--                        <path d="M12 2a10 10 0 0 0-9.95 9h11.64L9.74 7.05a1 1 0 0 1 1.41-1.41l5.66 5.65a1 1 0 0 1 0 1.42l-5.66 5.65a1 1 0 0 1-1.41 0 1 1 0 0 1 0-1.41L13.69 13H2.05A10 10 0 1 0 12 2z"></path>--}}
{{--                    </svg>--}}
{{--                    <span class="nhsuk-action-link__text">{{ __('Request 360 Review') }}</span>--}}
{{--                </a>--}}
{{--            </div>--}}
        @endif

        @if ($this->nodes?->count())

            @foreach ($paginatedNodes as $node)

                @if(!empty($this->headingHierarchy()))
                    @foreach ($this->headingHierarchy() as $item)
                        <{{ $item['headingTag'] }} class="{{ $item['headingClass'] }}">
                        <span class="nhsuk-tag--{{ $item['colour'] }} nhsuk-tag--no-border nhsuk-u-padding-2 tag-inline-wrap">
                            {{ $item['name'] }}
                        </span>
                    </{{ $item['headingTag'] }}>
                    @endforeach
                @else
                    <h1 class="nhsuk-heading-l" >
                        <span class="nhsuk-tag--{{ $node->colour ?? 'blue' }} nhsuk-tag--no-border nhsuk-u-padding-2">
                          {{$node?->parent?->parent?->name}}
                        </span>
                    </h1>
                    <h2 class="nhsuk-heading-m">
                        <span class="nhsuk-tag--{{ $node->colour ?? 'blue' }} nhsuk-tag--no-border nhsuk-u-padding-2 tag-inline-wrap">
                          {{$node?->parent?->name}}
                        </span>
                    </h2>
                    <h3 class="nhsuk-heading-s">
                        <span class="nhsuk-tag--{{ $node->colour ?? 'blue' }} nhsuk-tag--no-border nhsuk-u-padding-2">
                          {{ $node->name ?? ''}}
                        </span>
                    </h3>
                @endif

                <p>{!! $currentNode->description ?? $node->description ?? '' !!}</p>

                @livewire('questions', ['assessmentId' => $this->assessmentId, 'nodeId' => $this->nodeId, 'edit' => $this->edit ?? null])
            @endforeach

            <hr>

        {{--@livewire('summary', ['frameworkId' => $this->assessment->framework->id, 'assessmentId' => $this->assessmentId])--}}

        @else
            <p>{{ __('Assessment not found or has been removed.') }}</p>

            <div class="nhsuk-back-link">
                <a class="nhsuk-back-link__link" wire:click.prevent="backPage()" href="{{ route('variants') }}">
                    <svg class="nhsuk-icon nhsuk-icon__chevron-left" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M8.5 12c0-.3.1-.5.3-.7l5-5c.4-.4 1-.4 1.4 0s.4 1 0 1.4L10.9 12l4.3 4.3c.4.4.4 1 0 1.4s-1 .4-1.4 0l-5-5c-.2-.2-.3-.4-.3-.7z"></path>
                    </svg>{{ __('Step back') }}</a>
            </div>
        @endif

    </div>
</div>
