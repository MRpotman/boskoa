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
    var span = modal ? modal.querySelector('.booking-modal-close') : null; 

    function closeModal() {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    function openModal() {
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    if (btn && modal) {
        btn.onclick = openModal;
        btn.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                openModal();
            }
        });
        btn.setAttribute('tabindex', '0');
        btn.setAttribute('role', 'button');
        btn.setAttribute('aria-haspopup', 'dialog');
        btn.setAttribute('aria-controls', 'booking-modal');
    }

    if (span && modal) {
        span.onclick = closeModal; 
    }

    if (modal) {
        window.onclick = function(event) {
            if (event.target == modal) {
                closeModal();
            }
        };
    }

    var form = document.getElementById('booking-form');
    if (form) {
        form.addEventListener('submit', handleFormSubmit);
    }

    checkContactStatus();
}

function checkContactStatus() {
    var url = new URL(window.location.href);
    var contactStatus = url.searchParams.get('contact');

    if (contactStatus === 'success' || contactStatus === 'error') {
        url.searchParams.delete('contact');
        window.history.replaceState({}, document.title, url.toString());
    }
}

function handleFormSubmit(e) {
    e.preventDefault();
    
    var form = document.getElementById('booking-form');
    var submitBtn = form.querySelector('.booking-submit-btn');
    var originalText = submitBtn ? submitBtn.textContent : 'Send Booking Request';
    
    
    executeRecaptcha()
        .then(function(token) {
            document.getElementById('recaptchaToken').value = token;
            
            if (submitBtn) {
                submitBtn.textContent = 'Sending...';
                submitBtn.disabled = true;
            }
            
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
    var url = new URL(window.location.href);
    var contactStatus = url.searchParams.get('contact');
    var modal = document.getElementById('booking-modal');

    if (contactStatus === 'success') {
        alert('Thank you! Your booking request has been sent successfully.');

        url.searchParams.delete('contact');
        window.history.replaceState({}, document.title, url.toString());

        if (modal) {
            modal.style.display = 'block';
        }

    } else if (contactStatus === 'error') {
        alert('There was an error sending your request. Please try again.');

        url.searchParams.delete('contact');
        window.history.replaceState({}, document.title, url.toString());

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

window.initBookingModal = initBookingModal;
window.executeRecaptcha = executeRecaptcha;
