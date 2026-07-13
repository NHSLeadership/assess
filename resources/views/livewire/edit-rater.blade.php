<div class="nhsuk-grid-row nhsuk-u-margin-bottom-5">
    <div class="nhsuk-grid-column-full">


        <h1 class="nhsuk-heading-l">
            {{
                $this->isEditMode()
                    ? __('pages.raters.edit-rater')
                    : __('pages.raters.add-rater')
            }}
        </h1>


        @include('livewire.alerts')

        <form wire:submit.prevent="store">
            @component('components.form.input', ['name' => 'name'])
                @slot('label')
                    Name
                @endslot
                @slot('placeholder')
                    Name of the rater
                @endslot
                @slot('hint')
                    The name of the rater you are adding to this assessment
                @endslot
            @endcomponent

            @component('components.form.input', ['name' => 'email'])
                @slot('label')
                    Email
                @endslot
                @slot('placeholder')
                    Email of the rater
                @endslot
                @slot('hint')
                    The email of the rater you are adding to this assessment
                @endslot
            @endcomponent

            @component('components.form.dropdown', ['name' => 'type', 'options_list' => $this->raterTypeList])
                @slot('label')
                    Type
                @endslot
                @slot('hint')
                    The type of rater you are adding to this assessment
                @endslot
            @endcomponent

            @include('livewire.partials.rater-group-selector')

            <div class="nhsuk-form-group">
                <button class="nhsuk-button" type="submit">
                    {{ $this->isEditMode() ? __('pages.raters.edit-rater-button') : __('pages.raters.add-rater-button') }}
                </button>
            </div>

        </form>
        <a class="nhsuk-back-link" href="{{ route('assessment-raters', ['assessmentId' => $this->assessmentId]) }}">
            {{ __('Back to raters') }}
        </a>



    </div>
</div>
