document.addEventListener('DOMContentLoaded', () => {

  const slides = document.querySelectorAll('.slide');
  const dots = document.querySelectorAll('.dot');


  if (!slides.length || !dots.length) return;

  let current = 0;

  function goToSlide(index) {
    slides[current].classList.remove('active');
    dots[current].classList.remove('active');

    current = index;

    slides[current].classList.add('active');
    dots[current].classList.add('active');
  }

  // Auto slide solo si hay más de 1 slide
  if (slides.length > 1) {
    setInterval(() => {
      goToSlide((current + 1) % slides.length);
    }, 5000);
  }

  dots.forEach(dot => {
    dot.addEventListener('click', () => {
      const index = parseInt(dot.dataset.index);
      if (!isNaN(index)) {
        goToSlide(index);
      }
    });
  });

});