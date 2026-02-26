document.querySelectorAll('.accordion-header').forEach(btn => {
    btn.addEventListener('click', () => {
        const body = btn.nextElementSibling;
        const isOpen = btn.getAttribute('aria-expanded') === 'true';

        // Cierra todos
        document.querySelectorAll('.accordion-header').forEach(b => {
            b.setAttribute('aria-expanded', 'false');
            b.nextElementSibling.classList.remove('open');
        });

        // Abre el clickeado si estaba cerrado
        if (!isOpen) {
            btn.setAttribute('aria-expanded', 'true');
            body.classList.add('open');
        }
    });
});
