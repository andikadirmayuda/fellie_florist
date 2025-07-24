import "./bootstrap";

import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

// Pastikan Alpine.js berjalan dengan benar
document.addEventListener('DOMContentLoaded', function() {
    if (typeof Alpine === 'undefined') {
        console.error('Alpine.js tidak ter-load dengan benar');
    } else {
        console.log('Alpine.js berhasil di-load');
    }
});
