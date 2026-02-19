/**
 * Booking Modal Handler
 * Maneja la lógica del modal de reserva con reCAPTCHA
 */

document.addEventListener('DOMContentLoaded', function() {
    initBookingModal();
});

function initBookingModal() {
    var modal = document.getElementById('booking-modal');
    var btn = document.getElementById('open-booking-modal');
    var span = document.getElementsByClassName('booking-modal-close')[0];

    if (btn && modal) {
        // Click abre modal
        btn.onclick = function() {
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        };
        // Teclado: Enter o barra espaciadora
        btn.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                modal.style.display = 'block';
                document.body.style.overflow = 'hidden';
            }
        });
        // Asegura accesibilidad
        btn.setAttribute('tabindex', '0');
        btn.setAttribute('role', 'button');
        btn.setAttribute('aria-haspopup', 'dialog');
        btn.setAttribute('aria-controls', 'booking-modal');
    }
    
    if (span && modal) {
        span.onclick = function() {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        };
    }
    
    if (modal) {
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        };
    }
    
    // Handle form submission with reCAPTCHA
    var form = document.getElementById('booking-form');
    if (form) {
        form.addEventListener('submit', handleFormSubmit);
    }
    
    // Check for contact success/error messages in URL
    checkContactStatus();
}

function handleFormSubmit(e) {
    e.preventDefault();
    
    var form = document.getElementById('booking-form');
    var submitBtn = form.querySelector('.booking-submit-btn');
    var originalText = submitBtn ? submitBtn.textContent : 'Send Booking Request';
    
    // Generate reCAPTCHA token first
    executeRecaptcha()
        .then(function(token) {
            document.getElementById('recaptchaToken').value = token;
            
            if (submitBtn) {
                submitBtn.textContent = 'Sending...';
                submitBtn.disabled = true;
            }
            
            // Now submit the form
            form.submit();
        })
        .catch(function(error) {
            console.error('reCAPTCHA error:', error);
            alert('Verification error. Please reload the page and try again.');
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }
        });
}

function checkContactStatus() {
    var urlParams = new URLSearchParams(window.location.search);
    var contactStatus = urlParams.get('contact');
    var modal = document.getElementById('booking-modal');
    
    if (contactStatus === 'success') {
        alert('Thank you! Your booking request has been sent successfully. We will contact you soon.');
        // Remove the query parameter from URL
        window.history.replaceState({}, document.title, window.location.pathname);
        // Open modal to show success state (optional)
        if (modal) {
            modal.style.display = 'block';
        }
    } else if (contactStatus === 'error') {
        alert('There was an error sending your request. Please try again.');
        window.history.replaceState({}, document.title, window.location.pathname);
        // Open modal to show error state (optional)
        if (modal) {
            modal.style.display = 'block';
        }
    }
}

/**
 * Ejecutar verificación de reCAPTCHA v3
 * @returns {Promise<string>} Token de reCAPTCHA
 */
function executeRecaptcha() {
    return new Promise(function(resolve, reject) {
        if (typeof grecaptcha === 'undefined') {
            reject(new Error('reCAPTCHA not loaded'));
            return;
        }

        if (typeof window.boskoaRecaptchaSiteKey === 'undefined') {
            reject(new Error('reCAPTCHA site key not found'));
            return;
        }

        grecaptcha.ready(function() {
            grecaptcha.execute(window.boskoaRecaptchaSiteKey, { action: 'contact_form' })
                .then(function(token) {
                    resolve(token);
                })
                .catch(function(error) {
                    reject(error);
                });
        });
    });
}

// Export functions for global use
window.initBookingModal = initBookingModal;
window.executeRecaptcha = executeRecaptcha;
