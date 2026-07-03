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
                    The name of the rater you are attaching to this assessment.
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
                    The email of the rater you are attaching to this assessment.
                @endslot
            @endcomponent

            @component('components.form.dropdown', ['name' => 'type', 'options_list' => $this->raterTypeList])
                @slot('label')
                    Type
                @endslot
                @slot('hint')
                    The type of rater you are attaching to this assessment.
                @endslot
            @endcomponent

            <div class="nhsuk-form-group">
                @component('components.form.dropdown', ['name' => 'groupId', 'options_list' => $this->raterGroupList])
                    @slot('label')
                        Group
                    @endslot
                    @slot('hint')
                        The rater group.
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
                    @component('components.form.input', ['name' => 'newGroupName'])
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
                <button class="nhsuk-button"
                        type="submit">Add rater
                </button>
            </div>

        </form>
        <a class="nhsuk-back-link" href="{{ route('assessment-raters', ['assessmentId' => $this->assessmentId]) }}">
            {{ __('Back to raters') }}
        </a>



    </div>
</div>
