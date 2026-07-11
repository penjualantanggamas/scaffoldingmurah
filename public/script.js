// Tangga Mas — basic interactivity

document.addEventListener('DOMContentLoaded', () => {
  initCarousels();

  const menuBtn = document.getElementById('menuBtn');
  const mobileNav = document.getElementById('mobileNav');

  if (menuBtn && mobileNav) {
    menuBtn.addEventListener('click', () => {
      const isHidden = mobileNav.classList.contains('hidden');
      mobileNav.classList.toggle('hidden', !isHidden ? true : false);
      mobileNav.classList.toggle('flex', isHidden);

      // Swap hamburger <-> close icon
      const icon = menuBtn.querySelector('i');
      icon.classList.toggle('fa-bars');
      icon.classList.toggle('fa-xmark');
    });

    // Close mobile nav when a link is clicked
    mobileNav.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', () => {
        mobileNav.classList.add('hidden');
        mobileNav.classList.remove('flex');
        const icon = menuBtn.querySelector('i');
        icon.classList.add('fa-bars');
        icon.classList.remove('fa-xmark');
      });
    });
  }
});

/**
 * Initializes every element with [data-carousel] on the page.
 * Supports: autoplay (via data-autoplay="ms"), prev/next buttons,
 * dot navigation, pause-on-hover/focus, and touch/mouse swipe.
 */
function initCarousels() {
  const carousels = document.querySelectorAll('[data-carousel]');

  carousels.forEach((root) => {
    const track = root.querySelector('.carousel-track');
    const slides = Array.from(root.querySelectorAll('.carousel-slide'));
    const dots = Array.from(root.querySelectorAll('.carousel-dot'));
    const prevBtn = root.querySelector('.carousel-prev');
    const nextBtn = root.querySelector('.carousel-next');
    const autoplayDelay = parseInt(root.dataset.autoplay, 10) || 0;

    if (!track || slides.length === 0) return;

    let currentIndex = 0;
    let autoplayTimer = null;

    function goTo(index) {
      currentIndex = (index + slides.length) % slides.length;
      track.style.transform = `translateX(-${currentIndex * 100}%)`;

      dots.forEach((dot, i) => {
        dot.classList.toggle('is-active', i === currentIndex);
      });
    }

    function next() {
      goTo(currentIndex + 1);
    }

    function prev() {
      goTo(currentIndex - 1);
    }

    function startAutoplay() {
      if (!autoplayDelay) return;
      stopAutoplay();
      autoplayTimer = setInterval(next, autoplayDelay);
    }

    function stopAutoplay() {
      if (autoplayTimer) {
        clearInterval(autoplayTimer);
        autoplayTimer = null;
      }
    }

    // Manual controls
    if (nextBtn) {
      nextBtn.addEventListener('click', () => {
        next();
        startAutoplay(); // reset timer after manual interaction
      });
    }

    if (prevBtn) {
      prevBtn.addEventListener('click', () => {
        prev();
        startAutoplay();
      });
    }

    dots.forEach((dot, i) => {
      dot.addEventListener('click', () => {
        goTo(i);
        startAutoplay();
      });
    });

    // Pause on hover / keyboard focus
    root.addEventListener('mouseenter', stopAutoplay);
    root.addEventListener('mouseleave', startAutoplay);
    root.addEventListener('focusin', stopAutoplay);
    root.addEventListener('focusout', startAutoplay);

    // Swipe / drag support (touch + mouse)
    let startX = 0;
    let isDragging = false;

    function dragStart(x) {
      isDragging = true;
      startX = x;
      stopAutoplay();
    }

    function dragEnd(x) {
      if (!isDragging) return;
      isDragging = false;
      const delta = x - startX;
      const threshold = 40; // px

      if (delta > threshold) {
        prev();
      } else if (delta < -threshold) {
        next();
      }
      startAutoplay();
    }

    root.addEventListener('touchstart', (e) => dragStart(e.touches[0].clientX), { passive: true });
    root.addEventListener('touchend', (e) => dragEnd(e.changedTouches[0].clientX));

    root.addEventListener('mousedown', (e) => dragStart(e.clientX));
    root.addEventListener('mouseup', (e) => dragEnd(e.clientX));

    // Init
    goTo(0);
    startAutoplay();
  });
}