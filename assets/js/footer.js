/**
 * Footer Contact Form Handler
 * Maneja la lógica del formulario de contacto con reCAPTCHA v3
 */

document.addEventListener('DOMContentLoaded', function() {
    initContactForm();
});

function initContactForm() {
    const form = document.getElementById('contactForm');
    const submitBtn = document.getElementById('submitBtn');
    const btnText = submitBtn ? submitBtn.querySelector('.btn-text') : null;

    if (!form || !submitBtn || !btnText) {
        console.warn('Contact form elements not found');
        return;
    }

    form.addEventListener('submit', handleFormSubmit);

    function handleFormSubmit(e) {
        e.preventDefault();

        // Deshabilitar botón durante el proceso
        submitBtn.disabled = true;
        const originalText = btnText.textContent;
        btnText.textContent = 'Verifying...';

        // Ejecutar reCAPTCHA
        executeRecaptcha()
            .then(token => {
                // Agregar token al campo oculto
                document.getElementById('recaptchaToken').value = token;
                btnText.textContent = 'Sending...';
                form.submit();
            })
            .catch(error => {
                console.error('reCAPTCHA error:', error);
                showNotification('error', 'Verification Error', 'Please reload the page and try again.');
                submitBtn.disabled = false;
                btnText.textContent = originalText;
            });
    }
}

/**
 * Ejecutar verificación de reCAPTCHA v3
 * @returns {Promise<string>} Token de reCAPTCHA
 */
function executeRecaptcha() {
    return new Promise((resolve, reject) => {
        if (typeof grecaptcha === 'undefined') {
            reject(new Error('reCAPTCHA not loaded'));
            return;
        }

        if (typeof window.boskoaRecaptchaSiteKey === 'undefined') {
            reject(new Error('reCAPTCHA site key not found'));
            return;
        }

        grecaptcha.ready(() => {
            grecaptcha.execute(window.boskoaRecaptchaSiteKey, { action: 'contact_form' })
                .then(token => resolve(token))
                .catch(error => reject(error));
        });
    });
}

/**
 * Mostrar notificaciones de contacto
 * @param {string} type - 'success' o 'error'
 * @param {string} title - Título de la notificación
 * @param {string} message - Mensaje de la notificación
 */
function showNotification(type, title, message) {
    const notification = document.createElement('div');
    notification.className = `contact-notification ${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            ${type === 'success' ? getSuccessIcon() : getErrorIcon()}
            <div>
                <strong>${escapeHtml(title)}</strong>
                <p>${escapeHtml(message)}</p>
            </div>
            <button class="notification-close" aria-label="Close notification">&times;</button>
        </div>
    `;

    // Agregar event listener al botón de cerrar
    notification.querySelector('.notification-close').addEventListener('click', () => {
        notification.remove();
    });

    document.body.appendChild(notification);

    // Auto-cerrar después de 5 segundos
    setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}

/**
 * SVG del ícono de éxito
 */
function getSuccessIcon() {
    return `
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <polyline points="22 4 12 14.01 9 11.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    `;
}

/**
 * SVG del ícono de error
 */
function getErrorIcon() {
    return `
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
            <line x1="12" y1="8" x2="12" y2="12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            <line x1="12" y1="16" x2="12.01" y2="16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        </svg>
    `;
}

/**
 * Escapar caracteres HTML para evitar XSS
 */
function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
}

// Exportar funciones para uso global
window.showNotification = showNotification;
window.initContactForm = initContactForm;