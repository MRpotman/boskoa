document.addEventListener('DOMContentLoaded', () => {
  const slides = document.querySelectorAll('.slide');
  const dots = document.querySelectorAll('.dot');
  let current = 0;

  function goToSlide(index) {
    slides[current].classList.remove('active');
    dots[current].classList.remove('active');

    current = index;

    slides[current].classList.add('active');
    dots[current].classList.add('active');
  }

  setInterval(() => {
    goToSlide((current + 1) % slides.length);
  }, 5000);

  dots.forEach(dot => {
    dot.addEventListener('click', () => {
      goToSlide(parseInt(dot.dataset.index));
    });
  });
});
