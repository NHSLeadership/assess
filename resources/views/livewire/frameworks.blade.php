<div class="nhsuk-grid-row nhsuk-u-margin-bottom-5">
    <div class="nhsuk-grid-column-full">

        @if ($this->framework)
            <h1>{{ $this->framework->name ?? __('Framework dashboard') }}</h1>

            <p>{{ $this->framework->description ?? '' }}</p>

            <div class="nhsuk-action-link">
                <a class="nhsuk-action-link__link"
                   href="{{ route('instructions', ['frameworkId' => $this->framework?->id]) }}">
                    <svg class="nhsuk-icon nhsuk-icon__arrow-right-circle" xmlns="http://www.w3.org/2000/svg"
                         viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M12 2a10 10 0 0 0-9.95 9h11.64L9.74 7.05a1 1 0 0 1 1.41-1.41l5.66 5.65a1 1 0 0 1 0 1.42l-5.66 5.65a1 1 0 0 1-1.41 0 1 1 0 0 1 0-1.41L13.69 13H2.05A10 10 0 1 0 12 2z"></path>
                    </svg>
                    <span class="nhsuk-action-link__text">{{ __('Start new self assessment') }}</span>
                </a>
            </div>
        @else
            <p>No frameworks found.</p>
        @endif

        @if ($this->assessments()?->count())
            <h3>{{ __('Assessments') }}</h3>

            <table class="nhsuk-table">
                <thead class="nhsuk-table__head">
                <tr>
                    <th scope="col" class="nhsuk-table__header">Framework</th>
                    <th scope="col" class="nhsuk-table__header">Last updated</th>
                    <th scope="col" class="nhsuk-table__header">Progress</th>
                    <th scope="col" class="nhsuk-table__header">Status</th>
                </tr>
                </thead>
                <tbody class="nhsuk-table__body">
                @foreach ($this->assessments() as $assessment)
                    <tr class="nhsuk-table__row">
                        <td class="nhsuk-table__cell">
                            <a href="{{ !empty($assessment->submitted_at)
                               ? route('summary', ['frameworkId' => $this->framework?->id, 'assessmentId' => $assessment->id])
                               : route('variants', ['frameworkId' => $assessment->framework?->id, 'assessmentId' => $assessment->id]) }}"
                               aria-describedby="{{ $assessment->slug }}-hint"
                               class="nhsuk-link">
                                {{ $assessment->framework?->name }}
                            </a>
                        </td>
                        <td class="nhsuk-table__cell">
                            {{ $this->displayAssessmentDate($assessment) }}
                        </td>
                        <td class="nhsuk-table__cell">
                            {{ $this->displayProgress($assessment) }}
                        </td>
                        <td class="nhsuk-table__cell">
                            @if (empty($assessment->submitted_at))
                                @if ($assessment->questions)
                                    <strong class="nhsuk-tag nhsuk-tag--red">{{ __('Not started') }}</strong>
                                @elseif ($assessment->responses?->count() === $assessment?->framework?->questions?->where('required', 1)->count())
                                    <strong class="nhsuk-tag nhsuk-tag--orange">{{ __('Ready') }}</strong>
                                @else
                                    <strong class="nhsuk-tag nhsuk-tag--blue">{{ __('Started') }}</strong>
                                @endif
                            @else
                                <strong class="nhsuk-tag nhsuk-tag--green">{{ __('Completed') }}</strong>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <div class="nhsuk-inset-text nhsuk-u-margin-top-1">
                <span class="nhsuk-u-visually-hidden">Information: </span>
                <p>You haven't started any assessments in this framework.</p>
            </div>
        @endif

    </div>
</div>

