/**
 * transport-view.js
 * Lógica para la página de vista de transporte (transport-view)
 * Encolar en functions.php solo en la página con template "Transport View"
 */

(function () {

    /* ── Modal open / close ─────────────────────────────────── */
    const modal   = document.getElementById('transport-modal');
    const openBtn = document.getElementById('open-transport-modal');
    const closeBtn = document.getElementById('close-transport-modal');

    function closeModal() {
        if (!modal) return;
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }

    openBtn?.addEventListener('click', () => {
        if (!modal) return;
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    });

    closeBtn?.addEventListener('click', closeModal);

    modal?.addEventListener('click', (e) => {
        if (e.target === modal) closeModal();
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeModal();
    });


    /* ── Round-trip toggle ──────────────────────────────────── */
    const radios        = document.querySelectorAll('#transport-modal input[name="trip_type"]');
    const returnSection = document.getElementById('return-flight-section');
    const modalOptions  = document.querySelectorAll('.transport-modal-trip-option');

    function syncTripType(value) {
        // active class
        modalOptions.forEach((opt) => opt.classList.remove('active'));
        const activeOpt = document.querySelector(`.transport-modal-trip-option input[value="${value}"]`);
        activeOpt?.closest('.transport-modal-trip-option')?.classList.add('active');

        // show/hide return section
        if (returnSection) {
            returnSection.style.display = (value === 'round_trip') ? 'block' : 'none';
        }

        // toggle required fields in return section
        const returnDate = document.getElementById('t_return_date');
        const returnTime = document.getElementById('t_return_time');
        if (returnDate) returnDate.required = (value === 'round_trip');
        if (returnTime) returnTime.required = (value === 'round_trip');
    }

    radios.forEach((radio) => {
        radio.addEventListener('change', () => syncTripType(radio.value));
    });

    // Sync hero trip selector → modal radios (solo cuando tipo_ruta === 'both')
    const heroRadios = document.querySelectorAll('.transport-trip-options input[type="radio"]');

    heroRadios.forEach((r) => {
        r.addEventListener('change', () => {
            const modalRadio = document.querySelector(`#transport-modal input[name="trip_type"][value="${r.value}"]`);
            if (modalRadio && !modalRadio.disabled) {
                modalRadio.checked = true;
                syncTripType(r.value);
            }
        });
    });

    // Estado inicial según radio prechecked
    const checkedRadio = document.querySelector('#transport-modal input[name="trip_type"]:checked');
    if (checkedRadio) syncTripType(checkedRadio.value);


    /* ── Accordion ──────────────────────────────────────────── */
    document.querySelectorAll('.accordion-header').forEach((btn) => {
        btn.addEventListener('click', () => {
            const expanded = btn.getAttribute('aria-expanded') === 'true';
            btn.setAttribute('aria-expanded', String(!expanded));
            btn.nextElementSibling?.classList.toggle('open', !expanded);
        });
    });

})();