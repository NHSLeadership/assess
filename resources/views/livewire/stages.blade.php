<div class="nhsuk-grid-row nhsuk-u-margin-bottom-5">
    <div class="nhsuk-grid-column-full">

        @if (!empty($this->stage))

            <h1 class="nhsuk-heading-xl">{{ $this->stage->name ?? $this->stage->label }}</h1>

            <p>
                {{ $this->stage->description }}
            </p>

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
                            @if ($framework->areas?->where('completed_at', null)?->count())
                                <strong class="nhsuk-tag nhsuk-tag--blue">{{ __('In Progress') }}</strong>
                            @else
                                <span>{{ __('Not started') }}</span>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>

            <a class="nhsuk-back-link" href="{{ route('stages') }}">
                Back
            </a>

        @else

            <h1 class="nhsuk-heading-xl">Standards and Competencies</h1>
            <p>The standards and competencies are arranged in 3 overarching focus areas. Each focus area contains 3 standards. Each standard has 3 competency descriptions appropriate to the level you work at. These are shown in the wheel below. Once you select your stage you will be able to access all the competency descriptors pertinent to you.</p>
{{--            <div class="nhsuk-action-link">--}}
{{--                <a class="nhsuk-action-link__link" href="{{ route('frameworks') }}">--}}
{{--                    <svg class="nhsuk-icon nhsuk-icon__arrow-right-circle" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true">--}}
{{--                        <path d="M0 0h24v24H0z" fill="none"></path>--}}
{{--                        <path d="M12 2a10 10 0 0 0-9.95 9h11.64L9.74 7.05a1 1 0 0 1 1.41-1.41l5.66 5.65a1 1 0 0 1 0 1.42l-5.66 5.65a1 1 0 0 1-1.41 0 1 1 0 0 1 0-1.41L13.69 13H2.05A10 10 0 1 0 12 2z"></path>--}}
{{--                    </svg>--}}
{{--                    <span class="nhsuk-action-link__text">{{ __('Start new self assessment') }}</span>--}}
{{--                </a>--}}
{{--            </div>--}}

            {{-- Show all stages--}}
            <ul class="nhsuk-grid-row nhsuk-card-group">
                @foreach ($this->stages() as $item)
                    <li class="nhsuk-card-group__item
                        @if ($loop->first) nhsuk-grid-column-full @else nhsuk-grid-column-one-half @endif
                    ">
                        <div class="nhsuk-card @if ($item->frameworks()->exists()) nhsuk-card--clickable @endif">
                            <div class="nhsuk-card__content nhsuk-card__content--primary">
                                <h2 class="nhsuk-card__heading nhsuk-heading-m">
                                    @if ($item->frameworks()->exists())
                                        <a href="{{ route('stages', ['stageId' => $item->id]) }}" class="nhsuk-card__link">{{ $item->name ?? $item->label }}</a>
                                    @else
                                        {{ $item->name ?? $item->label }}
                                    @endif
                                </h2>
                                <p class="nhsuk-card__description">
                                    {{ $item->description ?? '' }}
                                </p>
                                @if ($item->frameworks()->exists())
                                    <svg class="nhsuk-icon nhsuk-icon--chevron-right-circle" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" focusable="false" aria-hidden="true">
                                        <path d="M12 2a10 10 0 1 1 0 20 10 10 0 0 1 0-20Zm-.3 5.8a1 1 0 1 0-1.5 1.4l2.9 2.8-2.9 2.8a1 1 0 0 0 1.5 1.4l3.5-3.5c.4-.4.4-1 0-1.4Z" />
                                    </svg>
                                @endif
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>

        @endif

    </div>

</div>

