document.addEventListener('DOMContentLoaded', () => {
  (() => {
    const svg = document.getElementById('toothSVG');
    if (!svg) return;

    const paths = Array.from(svg.querySelectorAll('path'));
    const baseStroke =
      getComputedStyle(document.documentElement)
        .getPropertyValue('--tooth-stroke').trim() || '#184b43';

    // Tweakables (desktop only)
    const START_X_VW  = -32;   // left (vw)
    const START_Y_VH  = -22;   // up   (vh)
    const START_SCALE = 1;
    const END_SCALE   = 1;

    // Stroke setup (outline only)
    const lengths = paths.map((p) => {
      const L = p.getTotalLength();
      p.style.fill = 'none';
      p.style.stroke = baseStroke;
      p.style.strokeWidth = '1';
      p.style.strokeLinecap = 'round';
      p.style.strokeLinejoin = 'round';
      p.style.strokeDasharray = L;
      p.style.strokeDashoffset = L;
      p.style.transition = 'stroke .25s ease';
      return L;
    });

    const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (reduce) {
      paths.forEach(p => { p.style.strokeDashoffset = 0; p.style.fill = 'none'; });
      svg.style.transform = 'none';
      return;
    }

    // Mobile/desktop switch
    const mqMobile = window.matchMedia('(max-width: 999.98px)');
    let motionEnabled = !mqMobile.matches; // false on mobile → draw only

    const clamp = (n, a=0, b=1) => Math.max(a, Math.min(b, n));
    const lerp  = (a, b, t) => a + (b - a) * t;
    const easeSmooth = (t) => t * t * (3 - 2 * t);     // for drawing
    const easeOut    = (t) => 1 - Math.pow(1 - t, 3);  // for motion

    function progressFor(el) {
      const r = el.getBoundingClientRect();
      const vh = window.innerHeight || document.documentElement.clientHeight;
      const start = vh * 0.15;
      const end   = r.height + vh * 0.60;
      const raw = (vh - r.top - start) / (end - start);
      return clamp(raw);
    }

    let ticking = false;

    function render() {
      const t  = progressFor(svg);
      const td = easeSmooth(t); // draw
      const tm = easeOut(t);    // motion

      // Draw stroke
      paths.forEach((p, i) => {
        p.style.strokeDashoffset = (1 - td) * lengths[i];
        p.style.fill = 'none';
      });

      // Motion only on desktop
      if (motionEnabled) {
        const tx = lerp(START_X_VW, 0, tm);
        const ty = lerp(START_Y_VH, 0, tm);
        const sc = lerp(START_SCALE, END_SCALE, tm);
        svg.style.transform = `translate(${tx}vw, ${ty}vh) scale(${sc})`;
      } else {
        svg.style.transform = 'none';
      }

      ticking = false;
    }

    function onScroll() {
      if (!ticking) {
        requestAnimationFrame(render);
        ticking = true;
      }
    }

    // React to viewport changes
    const handleMQ = (e) => {
      motionEnabled = !e.matches;
      // reset transform when entering mobile
      if (!motionEnabled) svg.style.transform = 'none';
      onScroll();
    };
    if (mqMobile.addEventListener) mqMobile.addEventListener('change', handleMQ);
    else mqMobile.addListener(handleMQ); // Safari fallback

    const io = new IntersectionObserver(([entry]) => {
      if (entry.isIntersecting) onScroll();
    }, { threshold: 0 });
    io.observe(svg);

    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll, { passive: true });

    onScroll();
  })();
});
