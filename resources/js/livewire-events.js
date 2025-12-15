// Events
Livewire.on('scroll-to-top', () => {
    window.scrollTo({top: 0});
});
Livewire.on('confirm-submit', () => {
    if (confirm('Are you sure you want to submit your assessment?')) {
        Livewire.dispatch('submitAssessmentConfirmed');
    }
});
