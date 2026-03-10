<div class="nhsuk-grid-row nhsuk-u-margin-bottom-5">
    <div class="nhsuk-grid-column-full">

        @include('livewire.alerts')

        @if (!empty($this->framework))
            <h1 class="nhsuk-heading-l">
                {{ $this->framework->name ?? null }} {{ strtolower(($this->loggedInRater($this->assessment)?->pivot?->assessment_type) ?? 'self assessment') }}
            </h1>
            <h2 class="nhsuk-heading-l">Assessment summary</h2>
            @if(empty($this->assessment?->submitted_at))
                <p>
                   {!! __('pages.summary.response-edit-prompt') !!}
                </p>
            @endif
        @endif

        @foreach ($this->rootNodes as $node)
            @include('livewire.summary-node', [
                'node'       => $node,
                'level'      => 0,
                'responses'  => $this->responses,
                'assessment' => $this->assessment(),
                'rater'      => $this->rater(),
            ])
        @endforeach

        @php
            $isSubmitted = (bool) $this->assessment->submitted_at;
            $hasAllRequired = $this->answeredRequiredCount === $this->requiredCount;
        @endphp

        @if (!$isSubmitted && !$hasAllRequired)
            <button class="nhsuk-button" wire:click.prevent="continueAssessment()">
                Continue assessment
            </button>
        @elseif (!$isSubmitted)
            <h2 class="nhsuk-heading-m">Complete your assessment</h2>
            <p>You will not be able to change your responses after completing your assessment</p>
            <button class="nhsuk-button"
                    wire:click.prevent="confirmSubmit()">
                Complete assessment
            </button>
        @else
            <button class="nhsuk-button"
                    wire:click.prevent="viewReport()">
                View report
            </button>
        @endif
    </div>
</div>
