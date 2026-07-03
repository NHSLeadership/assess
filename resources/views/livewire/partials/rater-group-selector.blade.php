<div class="nhsuk-form-group">

    @component('components.form.dropdown', [
        'name' => 'groupId',
        'options_list' => $this->raterGroupList
    ])
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