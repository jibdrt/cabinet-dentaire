document.addEventListener('DOMContentLoaded', () => {
  (() => {
    const svg = document.getElementById('toothSVG');
    if (!svg) return;

    const paths = Array.from(svg.querySelectorAll('path'));
    const baseStroke = getComputedStyle(document.documentElement)
      .getPropertyValue('--tooth-stroke').trim() || '#CBAE70';

    // Prime strokes and measure
    const lengths = paths.map(p => {
      const L = p.getTotalLength();
      p.style.fill = 'none';
      p.style.stroke = baseStroke;
      p.style.strokeWidth = '1';
      p.style.strokeDasharray = L;
      p.style.strokeDashoffset = L;
      p.style.transition = 'stroke .25s ease';
      return L;
    });

    // Build cumulative table so we can map a single pen position to each path
    const cum = [];
    let acc = 0;
    for (let i = 0; i < lengths.length; i++) {
      cum[i] = acc;          // start position of path i along the whole route
      acc += lengths[i];
    }
    const totalLen = acc;

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
      paths.forEach(p => (p.style.strokeDashoffset = 0));
      return;
    }

    function progressFor(el) {
      const r = el.getBoundingClientRect();
      const vh = window.innerHeight || document.documentElement.clientHeight;
      const start = vh * 0.40;
      const end = r.height + vh * 0.55;
      const raw = (vh - r.top - start) / (end - start);
      return Math.max(0, Math.min(1, raw));
    }

    let ticking = false;

    function render() {
      const t = progressFor(svg);
      const s = t * totalLen; // current pen position along the entire route

      paths.forEach((p, i) => {
        const L = lengths[i];
        const start = cum[i];
        const end = start + L;

        let offset; // how much of this path is still hidden
        if (s <= start) {
          offset = L;                       // not reached yet
        } else if (s >= end) {
          offset = 0;                       // fully drawn
        } else {
          offset = L - (s - start);         // partially drawn
        }
        p.style.strokeDashoffset = offset;
      });

      ticking = false;
    }

    function onScroll() {
      if (!ticking) {
        requestAnimationFrame(render);
        ticking = true;
      }
    }

    const io = new IntersectionObserver((entries) => {
      if (entries[0].isIntersecting) onScroll();
    });
    io.observe(svg);

    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll, { passive: true });

    onScroll(); // initial
  })();
});
