

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Custom HTML5 Form Validation in Indonesian
document.addEventListener('DOMContentLoaded', function() {
    // Apply to all inputs, selects, and textareas
    const elements = document.querySelectorAll('input, select, textarea');
    
    elements.forEach(el => {
        el.addEventListener('invalid', function(e) {
            if (e.target.validity.valueMissing) {
                e.target.setCustomValidity('Mohon isi bagian ini.');
            } else if (e.target.validity.typeMismatch) {
                if (e.target.type === 'email') {
                    e.target.setCustomValidity('Mohon masukkan format email yang valid.');
                } else if (e.target.type === 'url') {
                    e.target.setCustomValidity('Mohon masukkan URL yang valid.');
                } else {
                    e.target.setCustomValidity('Format tidak sesuai.');
                }
            } else if (e.target.validity.patternMismatch) {
                e.target.setCustomValidity('Format isian tidak sesuai.');
            } else if (e.target.validity.tooShort) {
                e.target.setCustomValidity('Isian terlalu pendek.');
            } else if (e.target.validity.tooLong) {
                e.target.setCustomValidity('Isian terlalu panjang.');
            } else {
                e.target.setCustomValidity('Data tidak valid.');
            }
        });

        // Clear custom validity on input so it can be re-evaluated
        el.addEventListener('input', function(e) {
            e.target.setCustomValidity('');
        });
    });
});
