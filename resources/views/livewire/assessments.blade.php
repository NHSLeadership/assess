<div class="nhsuk-grid-row nhsuk-u-margin-bottom-5">
    <div class="nhsuk-grid-column-full">
        <h1>{{ __('Assessment panel') }}</h1>

        @if (Auth::user())
        <h4>Welcome {{ Auth::user()->name ?? '' }}</h4>
        @endif

        <div class="nhsuk-grid-row">
            <div class="nhsuk-grid-column-one-half">
                <p>Self assessment allows you to reflect on how you meet the Management and Leadership Framework at your stage.</p>
                <div class="nhsuk-action-link">
                    <a class="nhsuk-action-link__link" href="{{ route('assessments') }}">
                        <svg class="nhsuk-icon nhsuk-icon__arrow-right-circle" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M12 2a10 10 0 0 0-9.95 9h11.64L9.74 7.05a1 1 0 0 1 1.41-1.41l5.66 5.65a1 1 0 0 1 0 1.42l-5.66 5.65a1 1 0 0 1-1.41 0 1 1 0 0 1 0-1.41L13.69 13H2.05A10 10 0 1 0 12 2z"></path>
                        </svg>
                        <span class="nhsuk-action-link__text">{{ __('Start new self assessment') }}</span>
                    </a>
                </div>
            </div>

            <div class="nhsuk-grid-column-one-half">
                <p>360 takes the self assessment a step further by seeking feedback from your line manager, peers and direct reports.</p>
                <div class="nhsuk-action-link disabled">
                    <a class="nhsuk-action-link__link disabled" href="#">
                        <svg class="nhsuk-icon nhsuk-icon__arrow-right-circle" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M12 2a10 10 0 0 0-9.95 9h11.64L9.74 7.05a1 1 0 0 1 1.41-1.41l5.66 5.65a1 1 0 0 1 0 1.42l-5.66 5.65a1 1 0 0 1-1.41 0 1 1 0 0 1 0-1.41L13.69 13H2.05A10 10 0 1 0 12 2z"></path>
                        </svg>
                        <span class="nhsuk-action-link__text">{{ __('Start new 360') }}</span>
                    </a>
                </div>
            </div>
        </div>

        <h3>{{ __('Available assessments') }}</h3>

        <ul class="nhsuk-task-list">
            @foreach ($this->assessments() as $assessment)
                <li class="nhsuk-task-list__item nhsuk-task-list__item--with-link">
                    <div class="nhsuk-task-list__name-and-hint">
                        <a href="{{ route('assessments', ['assessmentId' => $assessment->id]) }}"
                           aria-describedby="{{ $assessment->slug }}-hint"
                           class="nhsuk-link nhsuk-task-list__link">{{ $assessment->name }}</a>
                    </div>
                    <div class="nhsuk-task-list__status nhsuk-task-list__status--completed">
                        @if ($assessment->forms()?->where('completed_at', null)?->exists())
                            <strong class="nhsuk-tag nhsuk-tag--blue">{{ __('In Progress') }}</strong>
                        @else
                            <span>{{ __('Not started') }}</span>
                        @endif
                    </div>
                </li>
            @endforeach
        </ul>

    </div>
</div>

