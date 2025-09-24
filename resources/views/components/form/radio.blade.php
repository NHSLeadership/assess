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

    @if (isset($hints) && $hints->has($name))
        <div class="nhsuk-hint" id="{{ $name }}-error-hint">
            {{ $hints->first($name) }}
        </div>
    @endif

    @if ($errors->has($name))
        <span class="nhsuk-error-message" id="{{ $name }}-error-error">
          <span class="nhsuk-u-visually-hidden">Error:</span>{{ $errors->first($name) }}
        </span>
    @endif

    <div class="nhsuk-radios">
        @if (count($options_list)===1)
            <?php $options_list[0] = 'None of the above'?>
        @endif
        @if (!empty($sort) && isset($options_list) && is_array($options_list))
            <?php asort($options_list);?>
        @endif
        @foreach ($options_list as $param_value => $param_name)
            <div class="nhsuk-radios__item">
                <?php $selected = ($param_value == old($name)) ? "checked" : ''?>
                    <input name="{{ $name }}" id="{{ $name }}[{{ $param_value }}]"
                    @if(isset($wire))
                        @foreach($wire as $wire_type => $wire_name)
                        wire:{{ $wire_type }}="{{ $wire_name }}"
                        @endforeach
                    @else
                        wire:model.live="{{ $name }}"
                    @endif
                    @if (isset($tabindex) && $param_value === array_key_first($dropdown_list))
                        tabindex="{{ $tabindex }}"
                    @endif
                    value="{{$param_value}}"
                    class="nhsuk-radios__input"
                    type="radio" {{$selected}}>
                <label class="nhsuk-label nhsuk-radios__label" for="{{ $name }}[{{ $param_value }}]">
                    <span>{{ $param_name ?? '' }}</span>
                </label>
            </div>
        @endforeach
    </div>
</div>
