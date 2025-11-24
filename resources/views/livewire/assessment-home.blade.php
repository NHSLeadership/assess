<div class="nhsuk-grid-row nhsuk-u-margin-bottom-5">
    <div class="nhsuk-grid-column-full">

        @if ($this->framework)
            <h1>{{ $this->framework->name ?? __('Framework dashboard') }}</h1>

            <p>{{ $this->framework->description ?? '' }}</p>

            <div class="nhsuk-action-link">
                <a class="nhsuk-action-link__link" href="{{ route('variant-selection') }}">
                    <svg class="nhsuk-icon nhsuk-icon__arrow-right-circle" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M12 2a10 10 0 0 0-9.95 9h11.64L9.74 7.05a1 1 0 0 1 1.41-1.41l5.66 5.65a1 1 0 0 1 0 1.42l-5.66 5.65a1 1 0 0 1-1.41 0 1 1 0 0 1 0-1.41L13.69 13H2.05A10 10 0 1 0 12 2z"></path>
                    </svg>
                    <span class="nhsuk-action-link__text">{{ __('Start new self assessment') }}</span>
                </a>
            </div>

        @elseif ($this->frameworks())

            <h3>{{ __('Available frameworks') }}</h3>

            <ul class="nhsuk-task-list">
                @foreach ($this->frameworks() as $framework)
                    <li class="nhsuk-task-list__item nhsuk-task-list__item--with-link">
                        <div class="nhsuk-task-list__name-and-hint">
                            <a href="{{ route('frameworks', ['frameworkId' => $framework->id]) }}"
                               aria-describedby="{{ $framework->slug }}-hint"
                               class="nhsuk-link nhsuk-task-list__link">{{ $framework->name ?? $item->label }}</a>
                        </div>
                        <div class="nhsuk-task-list__status nhsuk-task-list__status--completed">
                            @if ($framework->nodes?->count())
                                <strong class="nhsuk-tag nhsuk-tag--blue">{{ __('In Progress') }}</strong>
                            @else
                                <span>{{ __('Not started') }}</span>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <p>No frameworks found.</p>
        @endif

        @if ($this->assessments()?->count())
            <h3>{{ __('Started assessments') }}</h3>

            <ul class="nhsuk-task-list">
                @foreach ($this->assessments() as $assessment)
                    <li class="nhsuk-task-list__item nhsuk-task-list__item--with-link">
                        <div class="nhsuk-task-list__name-and-hint">
{{--                            <a href="{{ route('assessments', ['assessmentId' => $assessment->id]) }}"--}}
{{--                               aria-describedby="{{ $assessment->slug }}-hint"--}}
{{--                               class="nhsuk-link nhsuk-task-list__link">{{ $assessment->framework?->name }}</a>--}}
                               - {{$assessment->created_at}}
                        </div>
                        <div class="nhsuk-task-list__status nhsuk-task-list__status--completed">
                            @if ($assessment->whereNull('completed_at'))
                                @if ($assessment->questions)
                                    <span>{{ __('Not started') }}</span>
                                @else
                                    <strong class="nhsuk-tag nhsuk-tag--blue">{{ __('In Progress') }}</strong>
                                @endif
                            @endif
                        </div>
                    </li>
                @endforeach

            </ul>
        @else
            <div class="nhsuk-inset-text nhsuk-u-margin-top-1">
                <span class="nhsuk-u-visually-hidden">Information: </span>
                <p>You haven't started any assessments in this framework.</p>
            </div>
        @endif

    </div>
</div>

