document.addEventListener('DOMContentLoaded', () => {
  (() => {
    const section = document.getElementById('intro-tooth');
    const wrap    = section?.querySelector('.tooth-wrap');
    const svg     = section?.querySelector('#toothSVG');
    if (!section || !wrap || !svg) return;

    const paths = Array.from(svg.querySelectorAll('path'));
    const baseStroke = getComputedStyle(document.documentElement)
      .getPropertyValue('--tooth-stroke').trim() || '#CBAE70';

    const isMobile = window.matchMedia('(max-width: 999px)').matches;
    const reduce   = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // ---- make sure we see the whole drawing on mobile
    // rely on CSS sizing; just avoid stroke overflow + keep thin stroke
    paths.forEach(p => {
      const L = (typeof p.getTotalLength === 'function') ? p.getTotalLength() : 0;
      p.style.fill = 'none';
      p.style.stroke = baseStroke;
      p.style.strokeWidth = isMobile ? '0.6' : '1.2';
      if (L) {
        p.style.strokeDasharray = L;
        p.style.strokeDashoffset = L;
      }
      p.style.vectorEffect = 'non-scaling-stroke';
    });
    svg.style.overflow = 'visible';

    // ---- draw logic
    const lengths = paths.map(p => (typeof p.getTotalLength === 'function') ? p.getTotalLength() : 0);
    const cum = [];
    let total = 0;
    for (let i = 0; i < lengths.length; i++) { cum[i] = total; total += lengths[i]; }

    // start earlier + partially pre-drawn on mobile
    const START_VH      = isMobile ? 0.20 : 0.40;
    const END_OFFSET_VH = isMobile ? 0.45 : 0.55;
    const START_PCT     = isMobile ? 0.05 : 0.00;

    function progressFor(el) {
      const r = el.getBoundingClientRect();
      const vh = window.innerHeight || document.documentElement.clientHeight;
      const start = vh * START_VH;
      const end   = r.height + vh * END_OFFSET_VH;
      const raw   = (vh - r.top - start) / (end - start);
      return Math.max(0, Math.min(1, raw));
    }

    // ---- small parallax on mobile
    const PARALLAX_MAX = isMobile && !reduce ? 40 : 0; // px downward

    if (reduce) {
      // draw immediately + no parallax
      paths.forEach(p => p.style.strokeDashoffset = 0);
      wrap.style.transform = 'translateY(0px)';
      return;
    }

    let rafing = false;
    function render() {
      const t = progressFor(svg);
      const s = total * (START_PCT + (1 - START_PCT) * t);

      // draw
      for (let i = 0; i < paths.length; i++) {
        const L = lengths[i];
        if (!L) continue;
        const a = cum[i], b = a + L;
        let off = L;
        if (s >= b) off = 0;
        else if (s > a) off = L - (s - a);
        paths[i].style.strokeDashoffset = off;
      }

      // parallax (downward as we scroll)
      if (PARALLAX_MAX) {
        // gentle ease so it feels smooth
        const eased = Math.pow(Math.min(1, Math.max(0, t)), 0.8);
        wrap.style.transform = `translateY(${(eased * PARALLAX_MAX).toFixed(2)}px)`;
      }

      rafing = false;
    }

    function onScroll() {
      if (!rafing) {
        rafing = true;
        requestAnimationFrame(render);
      }
    }

    const io = new IntersectionObserver((entries) => {
      if (entries[0].isIntersecting) onScroll();
    });
    io.observe(section);

    window.addEventListener('scroll', onScroll,  { passive: true });
    window.addEventListener('resize', onScroll,  { passive: true });
    onScroll();
  })();
});
