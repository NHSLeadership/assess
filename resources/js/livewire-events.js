// Events
Livewire.on('scroll-to-top', () => {
    window.scrollTo({top: 0});
});
Livewire.on('page-title', (event) => {
    document.title = event.title;
});
