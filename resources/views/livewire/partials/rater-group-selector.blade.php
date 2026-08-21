<div class="nhsuk-form-group">

    @component('components.form.dropdown', [
        'name' => 'groupId',
        'options_list' => $this->raterGroupList
    ])
        @slot('label')
            Group
        @endslot

        @slot('hint')
            The rater group. Grouping raters is optional. Use groups to organise raters, for example by team or department. Any group you create can be assigned to multiple raters.
        @endslot
    @endcomponent

    <a
        href="{{ route('manage-rater-groups', ['assessmentId' => $this->assessmentId,]) }}"
        class="nhsuk-link nhsuk-u-display-block nhsuk-u-margin-top-2">
        Manage groups
    </a>

</div>
