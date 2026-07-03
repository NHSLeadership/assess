<p class="nhsuk-body-s">{{ $message ?? __('Are you sure you want to detach this rater?') }}</p>
<div class="nhsuk-button-group">
    <button
            type="button"
            wire:click="{{ $confirmAction ?? 'confirmDetach' }}"
            class="nhsuk-button nhsuk-button--warning nhsuk-button--small"
            data-prevent-double-click="true">
        {{ $confirmLabel ?? __('Detach') }}
    </button>
    <button
            type="button"
            wire:click="{{ $cancelAction ?? 'cancelDetach' }}"
            class="nhsuk-button nhsuk-button--secondary nhsuk-button--small">
        {{ $cancelLabel ?? __('Cancel') }}
    </button>
</div>
