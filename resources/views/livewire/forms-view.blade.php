<div class="nhsuk-grid-row nhsuk-u-margin-bottom-5">
    <div class="nhsuk-grid-column-full">
        <h1>{{ __('Self Assessment') }}</h1>

        @if (!empty($this->stage()))
            <details class="nhsuk-details">
                <summary class="nhsuk-details__summary">
                    <h4 class="nhsuk-details__summary-text nhsuk-u-margin-0">
                       {{ $this->stage()->name ?? '' }}
                    </h4>
                </summary>
                <div class="nhsuk-details__text">
                    {{ $this->stage()->description ?? '' }}
                </div>
            </details>
        @endif

        <p>Please review the questions in the code and each of the competency areas below to complete your 360. You can work through the competency areas in any order.</p>

        <h3>{{ __('Self-assessment competencies') }}</h3>

        <ul class="nhsuk-task-list">
        @foreach ($this->forms() as $form)
            <li class="nhsuk-task-list__item nhsuk-task-list__item--with-link nhsuk-u-padding-left-2">
                <div class="nhsuk-task-list__name-and-hint">
                    <a href="{{ route('form', ['formId' => $form->id]) }}"
                       aria-describedby="{{ $form->slug }}-hint"
                       class="nhsuk-link nhsuk-task-list__link">{{$form->name}}</a>
                    <div class="nhsuk-task-list__hint">
                        <span class="nhsuk-tag nhsuk-tag--no-border nhsuk-tag--{{ $form->formArea->colour ?? 'blue' }}">{{$form->formArea->name}}</span>
                    </div>
                </div>

                @if ($this->userData()?->where('form_id', $form->id)->count() > 0)
                    @if ($this->userData()?->where('form_id', $form->id)->count() === $form->fields?->where('required', 1)->count())
                        <div class="nhsuk-task-list__status nhsuk-task-list__status--completed">
                            {{ __('Completed') }}
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
