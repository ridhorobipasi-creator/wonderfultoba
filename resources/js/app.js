import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Alpine.start() dispatches the real `alpine:init` event, at which point inline
// scripts registering via addEventListener('alpine:init', ...) run exactly once.
Alpine.start();
