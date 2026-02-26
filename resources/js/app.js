import './bootstrap';

import Alpine from 'alpinejs';
import { init as turboInit } from './turbo-navigation';

window.Alpine = Alpine;

Alpine.start();

// Initialize PJAX navigation after DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', turboInit);
} else {
    turboInit();
}
