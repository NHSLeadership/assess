<div class="nhsuk-form-group {{ $class ?? '' }} {{ $errors->has($name) ? ' nhsuk-form-group--error' : '' }}">

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
            <span class="nhsuk-u-visually-hidden">Error:</span>{!! $errors->first($name) !!}
        </span>
    @endif

    <input  name="{{ $name }}"
            id="{{ $name }}"
            class="nhsuk-input {{ $errors->has($name) ? ' nhsuk-input--error' : '' }}"
            type="{{ $type ?? 'text' }}"
            autocomplete="{{ $autocomplete ?? 'on' }}"
            placeholder="{{ $placeholder ?? '' }}"
            @if (isset($tabindex))
                tabindex="{{ $tabindex }}"
            @endif
            @if(isset($wire))
                @foreach($wire as $wire_type => $wire_name)
                wire:{{ $wire_type }}="{{ $wire_name }}"
                @endforeach
            @else
                wire:model.live="{{ $name }}"
            @endif
            value="{{ $value ?? old($name) }}"><br/>

</div>
