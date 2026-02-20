

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


  function checkBackground() {
    const headerHeight = header.offsetHeight;
    const elementBelow = document.elementFromPoint(
      window.innerWidth / 2,
      headerHeight + 5
    );

    if (!elementBelow) return;

    const lightSection = elementBelow.closest(".light-section");

    if (lightSection) {
      header.classList.add("light-bg");
    } else {
      header.classList.remove("light-bg");
    }
  }

  checkBackground();
  window.addEventListener("scroll", checkBackground);
});

