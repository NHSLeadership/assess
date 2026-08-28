<div class="nhsuk-grid-row nhsuk-u-margin-bottom-5">
    <div class="nhsuk-grid-column-full">

        <h1 class="nhsuk-heading-l">
            Select existing rater
        </h1>

        @include('livewire.alerts')

        <form wire:submit.prevent="store">

            @if (!empty($this->raterList))
                <div class="nhsuk-form-group">
                    @component('components.form.dropdown', [
                        'name' => 'selectedRaterId',
                        'options_list' => $this->raterList
                    ])
                        @slot('label')
                            Rater
                        @endslot

                        @slot('hint')
                            Select an existing rater to attach to this assessment.
                        @endslot
                    @endcomponent
                </div>

                <div class="nhsuk-form-group">
                    @component('components.form.dropdown', [
                        'name' => 'type',
                        'options_list' => $this->raterTypeList
                    ])
                        @slot('label')
                            Type
                        @endslot

                        @slot('hint')
                            The type of rater for this assessment.
                        @endslot
                    @endcomponent
                </div>

                @include('livewire.partials.rater-group-selector')

                <div class="nhsuk-form-group">

                    <button
                            class="nhsuk-button"
                            type="submit">
                        Select rater
                    </button>

                </div>
            @else
                <div class="nhsuk-form-group">
                    <p class="nhsuk-body-m">
                        No raters found. Please create a new rater first <a href="{{ route('create-rater', ['assessmentId' => $this->assessmentId]) }}" class="nhsuk-link">here</a>.
                    </p>
                </div>
            @endif

        </form>

        <a class="nhsuk-back-link" href="{{ route('assessment-raters', ['assessmentId' => $this->assessmentId]) }}">
            {{ __('Raters') }}
        </a>

    </div>
</div>
