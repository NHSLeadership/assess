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
            {!! $hints->first($name) !!}
        </div>
    @endif

    @if ($errors->has($name))
        <span class="nhsuk-error-message" id="{{ $name }}-error">
            <span class="nhsuk-u-visually-hidden">Error:</span>{{ $errors->first($name) }}
        </span>
    @endif

    <select name="{{ $name }}" id="{{ $name }}"
        @if(isset($wire))
            @foreach($wire as $wire_type => $wire_name)
            wire:{{ $wire_type }}="{{ $wire_name }}"
            @endforeach
        @else
            wire:model.live="{{ $name }}"
        @endif
        @if (isset($tabindex))
            tabindex="{{ $tabindex }}"
        @endif
        @if (!empty($disabled))
            disabled
        @endif
        class="nhsuk-select">

        <option value="">{{ $placeholder ?? '-- Please select --' }}</option>
        @if (isset($options_list) && is_array($options_list))

            @if (!empty($sort))
                @php asort($options_list); @endphp
            @endif

            @foreach ($options_list as $param_value => $param_name)
                <?php $selected = null ?>
                <option value="{{ $param_value }}" {{ $selected }}>{{ $param_name }}</option>
            @endforeach

        @endif
    </select>

</div>
