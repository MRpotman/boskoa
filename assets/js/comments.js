document.addEventListener('DOMContentLoaded', () => {
  const carousel = document.querySelector('.comment-carousel');
  const slidesContainer = document.querySelector('.comment-slides');
  const prevBtn = document.querySelector('.comment-arrow.prev');
  const nextBtn = document.querySelector('.comment-arrow.next');

  let slides;
  let visibleSlides;
  let index;
  let interval;

  function getVisibleSlides() {
    const width = window.innerWidth;
    if (width < 640) return 1;
    if (width < 1024) return 2;
    return 3;
  }

  function setupCarousel() {
    clearInterval(interval);

    // Reset container
    slidesContainer.innerHTML = slidesContainer.dataset.original;
    slides = Array.from(slidesContainer.children);

    visibleSlides = getVisibleSlides();
    index = visibleSlides;

    if (slides.length <= visibleSlides) return;

    // Clones
    slides.slice(-visibleSlides).forEach(slide => {
      slidesContainer.prepend(slide.cloneNode(true));
    });

    slides.slice(0, visibleSlides).forEach(slide => {
      slidesContainer.append(slide.cloneNode(true));
    });

    updateSlide(false);
    startAutoSlide();
  }

  function updateSlide(animate = true) {
    slidesContainer.style.transition = animate ? 'transform 0.5s ease' : 'none';
    slidesContainer.style.transform = `translateX(-${index * (100 / visibleSlides)}%)`;
  }

  function nextSlide() {
    index++;
    updateSlide();

    if (index >= slidesContainer.children.length - visibleSlides) {
      setTimeout(() => {
        index = visibleSlides;
        updateSlide(false);
      }, 500);
    }
  }

  function prevSlide() {
    index--;
    updateSlide();

    if (index <= 0) {
      setTimeout(() => {
        index = slidesContainer.children.length - visibleSlides * 2;
        updateSlide(false);
      }, 500);
    }
  }

  function startAutoSlide() {
    interval = setInterval(nextSlide, 3000);
  }

  function resetAutoSlide() {
    clearInterval(interval);
    startAutoSlide();
  }

  // Guardar HTML original
  slidesContainer.dataset.original = slidesContainer.innerHTML;

  nextBtn.addEventListener('click', () => {
    nextSlide();
    resetAutoSlide();
  });

  prevBtn.addEventListener('click', () => {
    prevSlide();
    resetAutoSlide();
  });

  window.addEventListener('resize', () => {
    setupCarousel();
  });

  setupCarousel();
});
