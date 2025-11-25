/**
 * NHS UK Frontend library
 */
import 'nhsuk-frontend/dist/nhsuk/nhsuk.js';

Livewire.on('message-displayed', () => {
	window.scrollTo({top: 0});
});
