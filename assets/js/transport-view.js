/**
 * transport-view.js
 * Lógica para la página de vista de transporte (transport-view)
 * Encolar en functions.php solo en la página con template "Transport View"
 */

(function () {

    const routeType = document.querySelector('.product-view-main-section[data-route-type]')
                        ?.dataset.routeType ?? '';

    /* ── Referencias DOM ────────────────────────────────────────────────── */
    const modal            = document.getElementById('transport-modal');
    const openBtn          = document.getElementById('open-transport-modal');
    const closeBtn         = document.getElementById('close-transport-modal');
    const modalRadios      = document.querySelectorAll('#transport-booking-form input[name="trip_type"]');
    const sectionArrival   = document.getElementById('flight-section-arrival');
    const sectionDeparture = document.getElementById('flight-section-departure');

    // Campos date/time de cada bloque (para manejar required dinámicamente)
    const arrivalDateFields   = sectionArrival   ? sectionArrival.querySelectorAll('input[type="date"], input[type="time"]')   : [];
    const departureDateFields = sectionDeparture ? sectionDeparture.querySelectorAll('input[type="date"], input[type="time"]') : [];


    /* ── Muestra/oculta secciones y ajusta required ─────────────────────── */
    function applyTripType(value) {
        const showArrival   = (value === 'arrival'   || value === 'round_trip');
        const showDeparture = (value === 'departure' || value === 'round_trip');

        if (sectionArrival)   sectionArrival.style.display   = showArrival   ? '' : 'none';
        if (sectionDeparture) sectionDeparture.style.display = showDeparture ? '' : 'none';

        // required solo en los campos actualmente visibles
        arrivalDateFields.forEach(el   => el.required = showArrival);
        departureDateFields.forEach(el => el.required = showDeparture);
    }


    /* ── Actualiza clase .active en los labels del modal ────────────────── */
    function syncActiveClass(value) {
        modalRadios.forEach(radio => {
            radio.closest('.transport-modal-trip-option')
                 ?.classList.toggle('active', radio.value === value);
        });
    }


    /* ── Cambio de radio dentro del modal ───────────────────────────────── */
    modalRadios.forEach(radio => {
        radio.addEventListener('change', () => {
            if (radio.checked) {
                syncActiveClass(radio.value);
                applyTripType(radio.value);
            }
        });
    });


    /* ── Sync hero → modal (solo cuando route_type === 'both') ─────────── */
    document.querySelectorAll('.transport-trip-options input[type="radio"]').forEach(heroRadio => {
        heroRadio.addEventListener('change', () => {
            if (!heroRadio.checked) return;
            const target = [...modalRadios].find(r => r.value === heroRadio.value && !r.disabled);
            if (target) {
                target.checked = true;
                syncActiveClass(target.value);
                applyTripType(target.value);
            }
        });
    });


    /* ── Abrir modal ────────────────────────────────────────────────────── */
    openBtn?.addEventListener('click', () => {
        if (!modal) return;

        // Hereda la selección del hero si existe
        const heroChecked  = document.querySelector('.transport-trip-options input[type="radio"]:checked');
        const modalChecked = document.querySelector('#transport-booking-form input[name="trip_type"]:checked');
        const preferredVal = heroChecked?.value ?? modalChecked?.value
                             ?? (routeType === 'round_trip' ? 'round_trip' : 'arrival');

        const target = [...modalRadios].find(r => r.value === preferredVal && !r.disabled);
        if (target) target.checked = true;

        const activeVal = document.querySelector('#transport-booking-form input[name="trip_type"]:checked')?.value
                          ?? preferredVal;

        syncActiveClass(activeVal);
        applyTripType(activeVal);

        modal.style.display          = 'flex';
        document.body.style.overflow = 'hidden';
    });


    /* ── Cerrar modal ───────────────────────────────────────────────────── */
    function closeModal() {
        if (!modal) return;
        modal.style.display          = 'none';
        document.body.style.overflow = '';
    }

    closeBtn?.addEventListener('click', closeModal);
    modal?.addEventListener('click', e => { if (e.target === modal) closeModal(); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });


    /* ── Estado inicial al cargar la página ─────────────────────────────── */
    const initialChecked = document.querySelector('#transport-booking-form input[name="trip_type"]:checked');
    if (initialChecked) applyTripType(initialChecked.value);


    /* ── Accordion ──────────────────────────────────────────────────────── */
    document.querySelectorAll('.accordion-header').forEach(btn => {
        btn.addEventListener('click', () => {
            const expanded = btn.getAttribute('aria-expanded') === 'true';
            btn.setAttribute('aria-expanded', String(!expanded));
            btn.nextElementSibling?.classList.toggle('open', !expanded);
        });
    });


    /* ── Envío del formulario con reCAPTCHA ─────────────────────────────── */
    const form = document.getElementById('transport-booking-form');
    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const submitBtn    = form.querySelector('.booking-submit-btn');
            const originalText = submitBtn ? submitBtn.textContent : 'Send Booking Request';

            if (typeof executeRecaptcha === 'function') {
                executeRecaptcha()
                    .then(function (token) {
                        document.getElementById('transportRecaptchaToken').value = token;
                        if (submitBtn) { submitBtn.textContent = 'Sending...'; submitBtn.disabled = true; }
                        form.submit();
                    })
                    .catch(function (error) {
                        console.error('reCAPTCHA error:', error);
                        alert('Verification error. Please reload the page and try again.');
                        if (submitBtn) { submitBtn.disabled = false; submitBtn.textContent = originalText; }
                    });

            } else if (typeof grecaptcha !== 'undefined' && typeof window.boskoaRecaptchaSiteKey !== 'undefined') {
                grecaptcha.ready(function () {
                    grecaptcha.execute(window.boskoaRecaptchaSiteKey, { action: 'contact_form' })
                        .then(function (token) {
                            document.getElementById('transportRecaptchaToken').value = token;
                            if (submitBtn) { submitBtn.textContent = 'Sending...'; submitBtn.disabled = true; }
                            form.submit();
                        });
                });

            } else {
                alert('reCAPTCHA not loaded. Please reload the page.');
            }
        });
    }

})();