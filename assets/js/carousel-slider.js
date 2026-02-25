document.querySelectorAll('.carousel-activities, .carousel-paquetes').forEach(carousel => {

    const cards = carousel.querySelectorAll('.package-card-carousel');
    const cardWidth = cards[0].offsetWidth + 16; // ancho + gap

    // 🔁 CLONAR PRIMERAS CARDS
    cards.forEach(card => {
        const clone = card.cloneNode(true);
        carousel.appendChild(clone);
    });

    let scrollAmount = 0;

    // Flechas
    const wrapper = carousel.closest('.carousel-wrapper');
    const btnLeft = wrapper.querySelector('.carousel-arrow.left');
    const btnRight = wrapper.querySelector('.carousel-arrow.right');

    btnRight.addEventListener('click', () => {
        scrollAmount += cardWidth;
        carousel.scrollTo({
            left: scrollAmount,
            behavior: "smooth"
        });
    });

    btnLeft.addEventListener('click', () => {
        scrollAmount -= cardWidth;
        carousel.scrollTo({
            left: scrollAmount,
            behavior: "smooth"
        });
    });

    // ♾ LOOP INFINITO
    carousel.addEventListener('scroll', () => {
        if (carousel.scrollLeft >= (cardWidth * cards.length)) {
            carousel.scrollLeft = 0;
            scrollAmount = 0;
        }
    });

});