<div class="nhsuk-grid-row nhsuk-u-margin-bottom-5">
    <div class="nhsuk-grid-column-full">

        @if ($this->assessment)

            <h1 class="nhsuk-heading-l">{{ __('pages.raters.title') }}</h1>

            <div class="nhsuk-grid-row">
                <div class="nhsuk-grid-column-one-half">
                    <div class="nhsuk-action-link">
                        <a class="nhsuk-action-link__link"
                           href="#" wire:click.prevent="addNewRater()">
                            <svg class="nhsuk-icon nhsuk-icon__arrow-right-circle" xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M12 2a10 10 0 0 0-9.95 9h11.64L9.74 7.05a1 1 0 0 1 1.41-1.41l5.66 5.65a1 1 0 0 1 0 1.42l-5.66 5.65a1 1 0 0 1-1.41 0 1 1 0 0 1 0-1.41L13.69 13H2.05A10 10 0 1 0 12 2z"></path>
                            </svg>
                            <span class="nhsuk-action-link__text">{{ __('Add new rater') }}</span>
                        </a>
                    </div>
                </div>
                <div class="nhsuk-grid-column-one-half">
                    <div class="nhsuk-action-link">
                        <a class="nhsuk-action-link__link"
                           href="#" wire:click.prevent="selectExistingRater()">
                            <svg class="nhsuk-icon nhsuk-icon__arrow-right-circle" xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M12 2a10 10 0 0 0-9.95 9h11.64L9.74 7.05a1 1 0 0 1 1.41-1.41l5.66 5.65a1 1 0 0 1 0 1.42l-5.66 5.65a1 1 0 0 1-1.41 0 1 1 0 0 1 0-1.41L13.69 13H2.05A10 10 0 1 0 12 2z"></path>
                            </svg>
                            <span class="nhsuk-action-link__text">{{ __('Select existing rater') }}</span>
                        </a>
                    </div>
                </div>
            </div>
        @endif

        @include('livewire.alerts')

        @if ($this->assessment->raters()->count() > 0)
            <table class="nhsuk-table nhsuk-table-responsive" role="table">
                <caption class="nhsuk-table__caption">
                    {{ __('Raters') }}
                </caption>
                <thead class="nhsuk-table__head" role="rowgroup">
                    <tr class="nhsuk-table__row" role="row">
                        <th scope="col" class="nhsuk-table__header" role="columnheader">Name</th>
                        <th scope="col" class="nhsuk-table__header" role="columnheader">Email</th>
                        <th scope="col" class="nhsuk-table__header" role="columnheader">Type</th>
                        <th scope="col" class="nhsuk-table__header" role="columnheader">Group</th>
                        <th scope="col" class="nhsuk-table__header" role="columnheader">Status</th>
                        <th scope="col" class="nhsuk-table__header" role="columnheader">Actions</th>
                    </tr>
                </thead>
                <tbody class="nhsuk-table__body">
                @foreach ($this->assessment->raters()->get() as $rater)
                    <tr class="nhsuk-table__row" wire:key="assessment-{{ $rater->id }}" role="row">
                        <td class="nhsuk-table__cell" role="cell">
                            <span class="nhsuk-table-responsive__heading">Name</span>
                            {{ $rater->name }}
                        </td>

                        <td class="nhsuk-table__cell" role="cell">
                            <span class="nhsuk-table-responsive__heading">Email</span>
                            {{ $rater->email }}
                        </td>
                        <td class="nhsuk-table__cell" role="cell">
                            <span class="nhsuk-table-responsive__heading">Type</span>
                            {{ $rater->pivot->type ?? '-' }}
                        </td>
                        <td class="nhsuk-table__cell" role="cell">
                            <span class="nhsuk-table-responsive__heading">Group</span>
                            {{ $rater->pivot->group->name ?? '-' }}

                        </td>
                        <td class="nhsuk-table__cell" role="cell">
                            <span class="nhsuk-table-responsive__heading">Status</span>
                            {{ $rater->pivot->getStatus() ?? '-' }}
                        </td>
                        <td class="nhsuk-table__cell" role="cell">
                            <span class="nhsuk-table-responsive__heading">Actions</span>
                            @if ($this->pendingDetachId === $rater->pivot->id)
                                @include('livewire.partials.confirm-detach')
                            @else
                                <button
                                        type="button"
                                        class="nhsuk-link"
                                        wire:click.prevent="askDetach({{ $rater->pivot->id }})">
                                    {{ __('Detach') }}
                                </button>
                                |
                                <button
                                        type="button"
                                        class="nhsuk-link"
                                        wire:click.prevent="editAssessmentRater({{ $rater->pivot->id }})">
                                    {{ __('Edit') }}
                                </button>
                                |
                                @if($rater->pivot->invited_at)
                                    <button
                                            type="button"
                                            class="nhsuk-link"
                                            title="Last invited: {{ $rater->pivot->invited_at->format('d/m/Y H:i') }}"
                                            wire:click.prevent="inviteRater({{ $rater->id }})">
                                        {{ __('Invite again') }}
                                    </button>
                                @else
                                    <button
                                            type="button"
                                            class="nhsuk-link"
                                            wire:click.prevent="inviteRater({{ $rater->id }})">
                                        {{ __('Invite') }}
                                    </button>
                                @endif
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <div class="nhsuk-inset-text nhsuk-u-margin-top-1">
                <span class="nhsuk-u-visually-hidden">Information: </span>
                <p>No raters have been added to this assessment yet.</p>
            </div>
        @endif

    </div>
</div>
