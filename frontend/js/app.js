/**
 * E-Medicine Global Scripts & Custom Popup System
 */

document.addEventListener("DOMContentLoaded", () => {
    console.log("%c E-Medicine Premium UI Loaded ", "background: #0d9488; color: #fff; padding: 4px; border-radius: 4px;");
    initializeMicroAnimations();
    setupCustomToasts();
});

function initializeMicroAnimations() {
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(btn => {
        btn.addEventListener('mousedown', function() { this.style.transform = 'scale(0.95)'; });
        btn.addEventListener('mouseup', function() { this.style.transform = 'scale(1)'; });
        btn.addEventListener('mouseleave', function() { this.style.transform = 'scale(1)'; });
    });
}

function setupCustomToasts() {
    // Inject Custom Toast Container
    const container = document.createElement('div');
    container.id = 'toast-container';
    document.body.appendChild(container);

    // Inject Toast CSS dynamically
    const style = document.createElement('style');
    style.innerHTML = `
        #toast-container { position: fixed; bottom: 30px; right: 30px; z-index: 999999; display: flex; flex-direction: column; gap: 12px; pointer-events: none; }
        .custom-toast { 
            background: #ffffff; 
            border-radius: 12px; 
            box-shadow: 0 15px 35px -5px rgba(0,0,0,0.25); 
            padding: 18px 24px; 
            display: flex; 
            align-items: center; 
            gap: 15px; 
            border-left: 5px solid #0d9488; 
            min-width: 300px;
            pointer-events: auto;
            transform: translateY(100%);
            opacity: 0;
            animation: toastPopIn 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        .custom-toast.error { border-left-color: #ef4444; }
        .custom-toast.success { border-left-color: #10b981; }
        .toast-icon { font-size: 1.6rem; }
        .toast-msg { font-size: 1rem; color: #1e293b; font-weight: 600; font-family: 'Inter', sans-serif; line-height: 1.4; }
        @keyframes toastPopIn { to { transform: translateY(0); opacity: 1; } }
        @keyframes toastSlideOut { to { transform: translateX(120%); opacity: 0; } }
    `;
    document.head.appendChild(style);

    // Completely override the native browser alert
    window.originalAlert = window.alert; 
    window.alert = function(message) {
        showCustomToast(message);
    };
}

function showCustomToast(message) {
    const container = document.getElementById('toast-container');
    if (!container) return; // Failsafe if body isn't ready

    const toast = document.createElement('div');
    let typeClass = 'success';
    let icon = '💡';

    // Simple heuristic to style based on message content
    const msgLower = String(message).toLowerCase();
    if (msgLower.includes('error') || msgLower.includes('fail') || msgLower.includes('invalid') || msgLower.includes('required')) {
        typeClass = 'error';
        icon = '⚠️';
    } else if (msgLower.includes('success') || msgLower.includes('added') || msgLower.includes('sent')) {
        typeClass = 'success';
        icon = '✅';
    }

    toast.className = `custom-toast ${typeClass}`;
    toast.innerHTML = `
        <div class="toast-icon">${icon}</div>
        <div class="toast-msg">${message}</div>
    `;
    container.appendChild(toast);

    // Remove the toast cleanly after 4 seconds
    setTimeout(() => {
        toast.style.animation = 'toastSlideOut 0.4s ease forwards';
        setTimeout(() => toast.remove(), 400); // Wait for animation out
    }, 4000);
}
