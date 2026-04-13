/**
 * E-Medicine Global Scripts
 * 
 * This file contains global JavaScript utilities and bootloaders
 * for the E-Medicine frontend application.
 */

document.addEventListener("DOMContentLoaded", () => {
    console.log("%c 🏥 E-Medicine Premium UI Loaded ", "background: #0d9488; color: #fff; font-size: 14px; padding: 4px; border-radius: 4px;");

    // Initialize any global components here
    initializeMicroAnimations();
});

function initializeMicroAnimations() {
    // A global utility to add visual feedback to any button clicks
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(btn => {
        btn.addEventListener('mousedown', function() {
            this.style.transform = 'scale(0.95)';
        });
        btn.addEventListener('mouseup', function() {
            this.style.transform = 'scale(1)';
        });
        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
}
