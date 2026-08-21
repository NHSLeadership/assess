<div class="nhsuk-grid-row">
    <div class="nhsuk-grid-column-full">

        <h1 class="nhsuk-heading-l">
            Manage groups
        </h1>

        @include('livewire.alerts')

        <table class="nhsuk-table">
            <thead>
            <tr>
                <th>Group</th>
                <th>Raters</th>
                <th>Actions</th>
            </tr>
            </thead>

            <tbody>
            @foreach ($this->groups as $group)
                <tr>
                    <td>{{ $group->name }}</td>

                    <td>{{ $group->assessment_raters_count }}</td>

                    <td>
                        <button
                                type="button"
                                class="nhsuk-link"
                                wire:click="editGroup({{ $group->id }})"
                        >
                            Edit
                        </button>

                        <button
                                type="button"
                                class="nhsuk-link"
                                wire:click="deleteGroup({{ $group->id }})"
                        >
                            Delete
                        </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <form wire:submit.prevent="saveGroup">

            <div class="nhsuk-form-group">
                <label class="nhsuk-label" for="name">
                    {{ $editingGroupId ? 'Rename group' : 'Add group' }}
                </label>

                <input
                        id="name"
                        class="nhsuk-input"
                        type="text"
                        wire:model="name"
                >
            </div>

            <button class="nhsuk-button">
                {{ $editingGroupId ? 'Update group' : 'Add group' }}
            </button>
        </form>
        <a
            class="nhsuk-back-link"
            href="{{ route('assessment-raters', ['assessmentId' => $assessmentId,]) }}"
        >
            Raters
        </a>
    </div>
</div>