

document.addEventListener('DOMContentLoaded', () => {
  const header = document.querySelector('.site-header');
  const menuCheckbox = document.getElementById('menu-checkbox');

  window.addEventListener('scroll', () => {
    if (window.scrollY > 80) {
      header.classList.add('scrolled');
    } else {
      header.classList.remove('scrolled');
    }
  });

  menuCheckbox.addEventListener('change', () => {
    if (menuCheckbox.checked) {
      header.classList.add('menu-open');
      document.body.style.overflow = "hidden"; // bloquea scroll
    } else {
      header.classList.remove('menu-open');
      document.body.style.overflow = "auto";
    }
  });
  window.addEventListener('resize', () => {
  if (window.innerWidth > 992) { // tu breakpoint
    document.body.style.overflow = "auto";
    menuCheckbox.checked = false;
    header.classList.remove('menu-open');
  }
});

});

