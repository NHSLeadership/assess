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

                <div class="nhsuk-form-group">

                    @component('components.form.dropdown', [
                        'name' => 'groupId',
                        'options_list' => $this->raterGroupList
                    ])
                        @slot('label')
                            Group
                        @endslot

                        @slot('hint')
                            Optional rater group.
                        @endslot
                    @endcomponent

                    <a href="#"
                       wire:click.prevent="$toggle('showNewGroup')"
                       class="nhsuk-link">
                        Add new group
                    </a>

                </div>

                @if($showNewGroup)

                    <div class="nhsuk-form-group">

                        @component('components.form.input', [
                            'name' => 'newGroupName'
                        ])
                            @slot('label')
                                New group name
                            @endslot
                        @endcomponent

                    </div>

                    <div class="nhsuk-form-group">

                        <button
                                type="button"
                                class="nhsuk-button nhsuk-button--secondary nhsuk-button--small"
                                wire:click="addGroup">
                            Save group
                        </button>

                        <button
                                type="button"
                                class="nhsuk-button nhsuk-button--warning nhsuk-button--small"
                                wire:click="cancelAddGroup">
                            Cancel
                        </button>

                    </div>

                @endif

                <div class="nhsuk-form-group">

                    <button
                            class="nhsuk-button"
                            type="submit">
                        Attach rater
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
            {{ __('Back to raters') }}
        </a>

    </div>
</div>
