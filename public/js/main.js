document.addEventListener('DOMContentLoaded', () => {

  /* ── Animated counters for stats ────────────────────── */
  const counters = document.querySelectorAll('.stat-number');

  const animateCounter = (el) => {
    const target = parseInt(el.textContent.replace(/\./g, ''), 10);
    const duration = 1200;
    const step = target / (duration / 16);
    let current = 0;

    const tick = () => {
      current += step;
      if (current < target) {
        el.textContent = Math.floor(current).toLocaleString('pt-PT');
        requestAnimationFrame(tick);
      } else {
        el.textContent = target.toLocaleString('pt-PT');
      }
    };
    tick();
  };

  /* Intersection Observer to trigger when visible */
  const io = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        animateCounter(entry.target);
        io.unobserve(entry.target);
      }
    });
  }, { threshold: 0.4 });

  counters.forEach(c => io.observe(c));

  /* ── Active nav link highlight ───────────────────────── */
  const navLinks = document.querySelectorAll('.nav-link');
  navLinks.forEach(link => {
    link.addEventListener('click', () => {
      navLinks.forEach(l => l.classList.remove('active'));
      link.classList.add('active');
    });
  });

  /* ── Scroll-reveal for section blocks ───────────────── */
  const revealEls = document.querySelectorAll(
    '.reported-item, .category-card, .step-card, .action-card'
  );

  const revealIO = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.style.opacity = '1';
        entry.target.style.transform = 'translateY(0)';
        revealIO.unobserve(entry.target);
      }
    });
  }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

  revealEls.forEach((el, i) => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(20px)';
    el.style.transition = `opacity .4s ease ${i * 0.07}s, transform .4s ease ${i * 0.07}s`;
    revealIO.observe(el);
  });

  /* ── Mobile navbar toggle (if needed) ───────────────── */
  // Placeholder for mobile menu implementation

  console.log('[Djumbai] App initialized successfully');
});
