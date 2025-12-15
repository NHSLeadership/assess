// Events
Livewire.on('scroll-to-top', () => {
    window.scrollTo({top: 0});
});
Livewire.on('confirm-submit', () => {
    if (confirm('Are you sure you want to submit your assessment?')) {
        Livewire.dispatch('submitAssessmentConfirmed');
    }
});

Livewire.on('redirect-to', ({ url }) => {
    // Give the alert a moment to render before navigating
    setTimeout(() => { window.location.href = url; }, 1200);
});