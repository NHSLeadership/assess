<div class="nhsuk-grid-row nhsuk-u-margin-bottom-5">
    <div class="nhsuk-grid-column-full">

        @if (!empty($this->framework))

            <h1 class="nhsuk-heading-xl">{{ $this->framework->name }}</h1>

            <p>{{ $this->framework->description }}</p>

            <h2 class="nhsuk-heading-l">Select stage</h2>

            {{-- Show all options--}}
            @if ($this->options())
                <ul class="nhsuk-grid-row nhsuk-card-group">
                    @foreach ($this->options() as $item)
                        <li class="nhsuk-card-group__item
                            @if ($loop->first) nhsuk-grid-column-full @else nhsuk-grid-column-one-half @endif
                        ">
                            <div class="nhsuk-card @if ($item->framework()->exists()) nhsuk-card--clickable @endif">
                                <div class="nhsuk-card__content nhsuk-card__content--primary">
                                    <h2 class="nhsuk-card__heading nhsuk-heading-m">
                                        @if ($item->framework()->exists())
{{--                                            <a href="{{ route('variant-assessment', ['frameworkId' => $this->frameworkId, 'stageId' => $item->id]) }}" class="nhsuk-card__link">{{ $item->label }}</a>--}}
                                            <a href="#" wire:click.prevent="newAssessment()" class="nhsuk-card__link">{{ $item->label }}</a>
                                        @else
                                            {{ $item->label }}
                                        @endif
                                    </h2>
                                    <p class="nhsuk-card__description">
                                        {{ $item->description ?? '' }}
                                    </p>
                                    @if ($item->framework()->exists())
                                        <svg class="nhsuk-icon nhsuk-icon--chevron-right-circle" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" focusable="false" aria-hidden="true">
                                            <path d="M12 2a10 10 0 1 1 0 20 10 10 0 0 1 0-20Zm-.3 5.8a1 1 0 1 0-1.5 1.4l2.9 2.8-2.9 2.8a1 1 0 0 0 1.5 1.4l3.5-3.5c.4-.4.4-1 0-1.4Z" />
                                        </svg>
                                    @endif
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p>No stages found in the selected framework.</p>
            @endif

        @endif

        <a class="nhsuk-back-link" href="{{ route('standards') }}">
            Back
        </a>

    </div>

</div>

