<div class="nhsuk-grid-row nhsuk-u-margin-bottom-5">
    <div class="nhsuk-grid-column-full">

        @if (!empty($this->framework))

            <h1 class="nhsuk-heading-xl">Instructions</h1>
            {!! $this->framework->instructions !!}
             <hr>
            <a class="nhsuk-button" href="{{ route('variants', ['frameworkId' => $this->framework?->id, 'assessmentId' => $this->assessment?->id]) }}">Continue</a>

            @if ($this->assessment?->framework?->questions?->where('required', 1)->count() && ($this->assessment?->responses?->count() === $this->assessment?->framework?->questions?->where('required', 1)->count()))
                <a class="nhsuk-button" href="{{ route('summary', ['frameworkId' => $this->frameworkId, 'assessmentId' => $this->assessmentId]) }}" >Finish assessment</a>
                <div class="nhsuk-inset-text">
                    <span class="nhsuk-u-visually-hidden">Information: </span>
                    <p>You completed all required fields, you can still navigate and change your answers or finish the assessment to receive a report.</p>
                </div>
            @endif

        @endif

    </div>

</div>

