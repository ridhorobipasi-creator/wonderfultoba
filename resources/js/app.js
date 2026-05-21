import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Dispatch alpine:init so inline script components can register before Alpine starts
document.dispatchEvent(new CustomEvent('alpine:init'));

Alpine.start();
