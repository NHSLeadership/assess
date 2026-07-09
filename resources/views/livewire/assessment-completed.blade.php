<div class="nhsuk-grid-row">
    <div class="nhsuk-grid-column-full">
        <div class="nhsuk-panel">
            <h1 class="nhsuk-panel__title">
                Assessment completed
            </h1>
            <div class="nhsuk-panel__body">
                @if ($this->assessmentCompletedDate() > now()->subHour())
                    Your assessment has been successfully completed.
                @else
                    Your assessment was successfully completed on {{ $this->assessmentCompletedDate()?->format('d M Y \a\t H:i') }}.
                @endif
            </div>
        </div>

        @if(empty($this->raterId))
            <button class="nhsuk-button"
                    wire:click.prevent="viewReport()">
                View report
            </button>
        @endif

        <h3>Need Help?</h3>
        <p>If you need help on this assessment, please <a href="https://leadershipacademy.nhs.uk/contact-us/">contact us</a> to let us know and we'll be
            happy to help.</p>

    </div>
</div>
