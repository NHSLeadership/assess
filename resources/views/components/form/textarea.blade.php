<div class="nhsuk-form-group {{ $errors->has($name) ? ' nhsuk-form-group--error' : '' }}">

    @if (isset($label))
        <label class="nhsuk-label nhsuk-label--s" for="{{$name}}">
            {{ $label }}
        </label>
    @endif

    @if (isset($hint))
        <div class="nhsuk-hint" id="{{$name}}-hint">
            {{ $hint }}
        </div>
    @endif

    @if (!empty($hints) && is_array($hints))
        <div class="nhsuk-hint" id="{{ $name }}-hints">
            @foreach($hints as $hint)
                <p>{!! $hint !!}</p>
            @endforeach
        </div>
    @endif

    @if ($errors->has($name))
        <span class="nhsuk-error-message" id="{{ $name }}-error-error">
            <span class="nhsuk-u-visually-hidden">Error:</span>{{ $errors->first($name) }}
        </span>
    @endif

    @php
        $describedBy = [];

        if (isset($hint)) {
            $describedBy[] = $name . '-hint';
        }

        if (!empty($hints)) {
            $describedBy[] = $name . '-hints';
        }

        if ($errors->has($name)) {
            $describedBy[] = $name . '-error-error';
        }
    @endphp

        <textarea name="{{ $name }}"
            id="{{ $name }}"
            class="nhsuk-textarea {{ $errors->has($name) ? ' nhsuk-textarea--error' : '' }} {{ $class ?? '' }}"
            placeholder="{{ $placeholder ?? '' }}"
            @if (isset($tabindex))
                tabindex="{{ $tabindex }}"
            @endif
            @if (isset($wire))
                @foreach($wire as $wire_type => $wire_name)
                wire:{{ $wire_type }}="{{ $wire_name }}"
                @endforeach
            @else
                wire:model.defer="{{ $name ?? null }}"
            @endif
            rows="{{ $rows ?? 5 }}"
            @if($describedBy) aria-describedby="{{ implode(' ', $describedBy) }}"@endif
        >
            {{ old($name) }}
        </textarea>
        <br/>
</div>
