<div class="nhsuk-form-group {{ $errors->has($name) ? ' nhsuk-form-group--error' : '' }}">

    @if (isset($label))
        <legend class="nhsuk-fieldset__legend nhsuk-fieldset__legend--s">
            <p class="nhsuk-fieldset__heading">
                {{ $label }}
            </p>
        </legend>
    @endif

    @if (isset($hint))
        <div class="nhsuk-hint" id="{{$name}}-hint">
            {{ $hint }}
        </div>
    @endif

    @if ($errors->has($name))
        <span class="nhsuk-error-message" id="{{ $name }}-error-error">
            <span class="nhsuk-u-visually-hidden">Error:</span>{{ $errors->first($name) }}
        </span>
    @endif

    <div class="nhsuk-date-input" id="example">
        <div class="nhsuk-date-input__item">
            <div class="nhsuk-form-group">
                <label class="nhsuk-label nhsuk-date-input__label" for="dateinput-day">{{__('Day')}}</label>
                <input wire:model.live="{{ $name }}.day" name="{{ $name }}[day]"
                       class="nhsuk-input nhsuk-date-input__input nhsuk-input--width-2" id="dateinput-day"
                       type="number" min="1" max="31" inputmode="numeric"
                       @empty($disabled) @else disabled @endempty>
            </div>
        </div>
        <div class="nhsuk-date-input__item">
            <div class="nhsuk-form-group">
                <label class="nhsuk-label nhsuk-date-input__label" for="dateinput-month">{{__('Month')}}</label>
                <input wire:model.live="{{ $name }}.month" name="{{ $name }}[month]"
                       class="nhsuk-input nhsuk-date-input__input nhsuk-input--width-2" id="dateinput-month"
                       type="number" min="1" max="12" inputmode="numeric"
                       @empty($disabled) @else disabled @endempty>
            </div>
        </div>
        <div class="nhsuk-date-input__item">
            <div class="nhsuk-form-group">
                <label class="nhsuk-label nhsuk-date-input__label" for="dateinput-year">{{__('Year')}}</label>
                <input wire:model.live="{{ $name }}.year" name="{{ $name }}[year]"
                       class="nhsuk-input nhsuk-date-input__input nhsuk-input--width-4" id="dateinput-year"
                       type="number" min="2024" max="{{ date('Y', strtotime('+2 years')) }}" inputmode="numeric"
                       @empty($disabled) @else disabled @endempty>
            </div>
        </div>

        @if (!isset($nohours))
            <div class="nhsuk-date-input__item">
                <div class="nhsuk-form-group">
                    <label class="nhsuk-label nhsuk-date-input__label" for="dateinput-hour">{{__('Hour')}}</label>
                    <input wire:model.live="{{ $name }}.hour" name="{{ $name }}[hour]"
                           class="nhsuk-input nhsuk-date-input__input nhsuk-input--width-2" id="dateinput-hour"
                           type="number" min="0" max="23" inputmode="numeric"
                           @empty($disabled) @else disabled @endempty>
                </div>
            </div>

            <div class="nhsuk-date-input__item">
                <div class="nhsuk-form-group">
                    <label class="nhsuk-label nhsuk-date-input__label" for="dateinput-minute">{{__('Minute')}}</label>
                    <input wire:model.live="{{ $name }}.minute" name="{{ $name }}[minute]"
                           class="nhsuk-input nhsuk-date-input__input nhsuk-input--width-2" id="dateinput-minute"
                           type="number" min="0" max="59" inputmode="numeric"
                           @empty($disabled) @else disabled @endempty>
                </div>
            </div>

            <div class="nhsuk-date-input__item">
                <div class="nhsuk-form-group">
                    <label class="nhsuk-label nhsuk-date-input__label" for="dateinput-second">{{__('Second')}}</label>
                    <input wire:model.live="{{ $name }}.second" name="{{ $name }}[second]"
                           class="nhsuk-input nhsuk-date-input__input nhsuk-input--width-2" id="dateinput-second"
                           type="number" min="0" max="59" inputmode="numeric"
                           @empty($disabled) @else disabled @endempty>
                </div>
            </div>
        @endif

    </div>

</div>
