<div class="nhsuk-grid-row nhsuk-u-margin-bottom-5">
    <div class="nhsuk-grid-column-full">

        @if (!empty($this->framework))
            <h3 class="nhsuk-heading-s">
                {{ $this->framework->name ?? '' }} summary
            </h3>
            <p>
                {{ $this->framework->description ?? '' }}
            </p>
        @endif

        <ul class="nhsuk-task-list">
        @foreach ($this->areas as $area)
            <li class="nhsuk-task-list__item nhsuk-task-list__item--with-link nhsuk-u-padding-left-2">
                <div class="nhsuk-task-list__name-and-hint">
                    {{ $area->name }}
                    @if ($area->parent)
                    <div class="nhsuk-task-list__hint">
                        <span class="nhsuk-tag nhsuk-tag--no-border nhsuk-tag--{{ $area->parent->colour ?? 'blue' }}">{{ $area->parent->name ?? '' }}</span>
                    </div>
                    @endif
                </div>

                @if ($this->userData?->where('formField.area_id', $area->id)->count())
                    @if ($this->userData?->where('formField.area_id', $area->id)->count() === $area->fields?->where('required', 1)->count())
                        <div class="nhsuk-task-list__status nhsuk-task-list__status--completed">
                            <strong class="nhsuk-tag nhsuk-tag--transparent nhsuk-tag--no-border">
                                {{ __('Completed') }}
                            </strong>
                        </div>
                    @else
                        <div class="nhsuk-task-list__status">
                            <strong class="nhsuk-tag nhsuk-tag--white">
                                {{ __('In progress') }}
                            </strong>
                        </div>
                    @endif
                @else
                    <div class="nhsuk-task-list__status">
                        <strong class="nhsuk-tag nhsuk-tag--blue">
                            {{ __('Not yet started') }}
                        </strong>
                    </div>
                @endif
            </li>
        @endforeach
        </ul>

    </div>
</div>
