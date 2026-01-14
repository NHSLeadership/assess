<div class="nhsuk-inset-text" role="status" aria-live="polite">
    <p class="nhsuk-body-s">{{ $message ?? __('Are you sure you want to delete this assessment?') }}</p>

    <div class="nhsuk-button-group">
        <button
                type="button"
                wire:click="{{ $confirmAction ?? 'confirmDelete' }}"
                class="nhsuk-button nhsuk-button--warning nhsuk-button--small"
                data-prevent-double-click="true">
            {{ $confirmLabel ?? __('Delete') }}
        </button>

        <button
                type="button"
                wire:click="{{ $cancelAction ?? 'cancelDelete' }}"
                class="nhsuk-button nhsuk-button--secondary nhsuk-button--small">
            {{ $cancelLabel ?? __('Cancel') }}
        </button>
    </div>
</div>