/**
 * Animated Pagination with AJAX
 * Carga rápida de contenido sin recargar toda la página
 */

(function() {
  'use strict';

  // Configuración
  const CONFIG = {
    selectors: {
      pagination: '.pagination',
      paginationButton: '.pagination-button',
      paginationCurrent: '.pagination-current',
      paginationWrapper: '.pagination-wrapper',
      toursGrid: '.tours-grid',
      prevButton: '.pagination-prev',
      nextButton: '.pagination-next'
    },
    animationDuration: 280,
    clickEffectDuration: 100,
    scrollOffset: 100
  };

  class PaginationController {
    constructor() {
      this.nav = document.querySelector(CONFIG.selectors.pagination);
      if (!this.nav) return;

      this.buttons = Array.from(this.nav.querySelectorAll(CONFIG.selectors.paginationButton));
      this.wrapper = document.querySelector(CONFIG.selectors.paginationWrapper);
      this.toursGrid = document.querySelector(CONFIG.selectors.toursGrid);
      this.prevBtn = document.querySelector(CONFIG.selectors.prevButton);
      this.nextBtn = document.querySelector(CONFIG.selectors.nextButton);
      
      if (this.buttons.length === 0) return;

      this.currentIndex = this.getCurrentIndex();
      this.isLoading = false;
      
      this.init();
    }

    getCurrentIndex() {
      const activeIndex = this.buttons.findIndex(b => b.classList.contains('active'));
      return activeIndex >= 0 ? activeIndex : 0;
    }

    init() {
      // Set initial CSS variable
      this.updateCSSIndex(this.currentIndex);

      // Attach event listeners
      this.attachButtonListeners();
      this.attachNavButtonListeners();
      this.attachKeyboardNavigation();
    }

    attachButtonListeners() {
      this.buttons.forEach((btn, index) => {
        // Click handler
        btn.addEventListener('click', (e) => {
          e.preventDefault();
          if (this.isLoading) return;
          this.goToPage(index, btn);
        });

        // Touch handler for mobile
        btn.addEventListener('touchstart', (e) => {
          e.preventDefault();
          if (this.isLoading) return;
          this.goToPage(index, btn);
        }, { passive: false });
      });
    }

    attachNavButtonListeners() {
      if (this.prevBtn) {
        this.prevBtn.addEventListener('click', (e) => {
          e.preventDefault();
          if (this.isLoading) return;
          if (e.currentTarget.classList.contains('disabled')) return;
          const targetIndex = Math.max(0, this.currentIndex - 1);
          if (targetIndex !== this.currentIndex) {
            this.goToPage(targetIndex, this.buttons[targetIndex]);
          }
        });
      }

      if (this.nextBtn) {
        this.nextBtn.addEventListener('click', (e) => {
          e.preventDefault();
          if (this.isLoading) return;
          if (e.currentTarget.classList.contains('disabled')) return;
          const targetIndex = Math.min(this.buttons.length - 1, this.currentIndex + 1);
          if (targetIndex !== this.currentIndex) {
            this.goToPage(targetIndex, this.buttons[targetIndex]);
          }
        });
      }
    }

    attachKeyboardNavigation() {
      this.nav.addEventListener('keydown', (e) => {
        if (this.isLoading) return;

        if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
          e.preventDefault();
          const next = Math.min(this.currentIndex + 1, this.buttons.length - 1);
          if (next !== this.currentIndex) {
            this.buttons[next].focus();
            this.goToPage(next, this.buttons[next]);
          }
        } else if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
          e.preventDefault();
          const prev = Math.max(this.currentIndex - 1, 0);
          if (prev !== this.currentIndex) {
            this.buttons[prev].focus();
            this.goToPage(prev, this.buttons[prev]);
          }
        }
      });
    }

    goToPage(index, buttonEl) {
      if (index === this.currentIndex || this.isLoading) return;

      const pageUrl = buttonEl.getAttribute('href');
      if (!pageUrl) return;

      // Update UI immediately for smooth animation
      this.updateActiveState(index);
      this.addClickEffect();

      // Try AJAX load first, fallback to full page load
      this.loadPageContent(pageUrl, index);
    }

    updateActiveState(index) {
      this.buttons.forEach((b, i) => {
        b.classList.toggle('active', i === index);
        if (i === index) {
          b.setAttribute('aria-current', 'page');
        } else {
          b.removeAttribute('aria-current');
        }
      });
      
      this.currentIndex = index;
      this.updateCSSIndex(index);
      this.updateNavButtons(index);
    }

    updateCSSIndex(index) {
      this.nav.style.setProperty('--index', String(index));
    }

    updateNavButtons(index) {
      // Update prev button
      if (this.prevBtn) {
        if (index === 0) {
          this.prevBtn.classList.add('disabled');
          this.prevBtn.setAttribute('aria-disabled', 'true');
        } else {
          this.prevBtn.classList.remove('disabled');
          this.prevBtn.removeAttribute('aria-disabled');
          const prevUrl = this.buttons[index - 1]?.getAttribute('href');
          if (prevUrl) this.prevBtn.setAttribute('href', prevUrl);
        }
      }

      // Update next button
      if (this.nextBtn) {
        if (index === this.buttons.length - 1) {
          this.nextBtn.classList.add('disabled');
          this.nextBtn.setAttribute('aria-disabled', 'true');
        } else {
          this.nextBtn.classList.remove('disabled');
          this.nextBtn.removeAttribute('aria-disabled');
          const nextUrl = this.buttons[index + 1]?.getAttribute('href');
          if (nextUrl) this.nextBtn.setAttribute('href', nextUrl);
        }
      }
    }

    addClickEffect() {
      this.nav.classList.add('clicking');
      setTimeout(() => {
        this.nav.classList.remove('clicking');
      }, CONFIG.clickEffectDuration);
    }

    async loadPageContent(url, targetIndex) {
      this.isLoading = true;
      this.wrapper?.classList.add('loading');

      try {
        // Fetch the page content
        const response = await fetch(url, {
          headers: {
            'X-Requested-With': 'XMLHttpRequest'
          }
        });

        if (!response.ok) throw new Error('Network response was not ok');

        const html = await response.text();
        
        // Parse the HTML
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const newContent = doc.querySelector(CONFIG.selectors.toursGrid);

        if (newContent && this.toursGrid) {
          // Wait for animation to complete
          await this.delay(CONFIG.animationDuration);

          // Fade out current content
          this.toursGrid.style.opacity = '0';
          this.toursGrid.style.transform = 'translateY(20px)';
          
          await this.delay(200);

          // Update content
          this.toursGrid.innerHTML = newContent.innerHTML;
          
          // Fade in new content
          requestAnimationFrame(() => {
            this.toursGrid.style.opacity = '1';
            this.toursGrid.style.transform = 'translateY(0)';
          });

          // Smooth scroll to top of content
          this.scrollToContent();

          // Update browser URL without reload
          window.history.pushState(
            { page: targetIndex + 1 },
            '',
            url
          );

        } else {
          // Fallback: full page load if we can't find the content
          throw new Error('Content not found');
        }

      } catch (error) {
        console.warn('AJAX load failed, falling back to full page load:', error);
        // Fallback to traditional navigation
        setTimeout(() => {
          window.location.href = url;
        }, CONFIG.animationDuration);
        return;
      } finally {
        this.isLoading = false;
        this.wrapper?.classList.remove('loading');
      }
    }

    scrollToContent() {
      const packagesSection = document.getElementById('packages');
      if (packagesSection) {
        const offset = packagesSection.offsetTop - CONFIG.scrollOffset;
        window.scrollTo({
          top: offset,
          behavior: 'smooth'
        });
      }
    }

    delay(ms) {
      return new Promise(resolve => setTimeout(resolve, ms));
    }
  }

  // Handle browser back/forward buttons
  window.addEventListener('popstate', function(event) {
    if (event.state && event.state.page) {
      // Reload the page when using back/forward
      window.location.reload();
    }
  });

  // Initialize when DOM is ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
      new PaginationController();
    });
  } else {
    new PaginationController();
  }

  // Add CSS transition for grid
  const style = document.createElement('style');
  style.textContent = `
    .tours-grid {
      transition: opacity 200ms ease, transform 200ms ease;
    }
  `;
  document.head.appendChild(style);

})();