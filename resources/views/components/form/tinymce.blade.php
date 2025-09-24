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

    <div wire:ignore>
        <textarea  wire:model.live="{{ $name }}" id="{{ $name }}" name="{{ $name }}" class="nhsuk-tinymce nhsuk-textarea">
            {!! $value !!}
        </textarea>
    </div>

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
</div>

@vite([
    'resources/js/tinymce.js'
])
