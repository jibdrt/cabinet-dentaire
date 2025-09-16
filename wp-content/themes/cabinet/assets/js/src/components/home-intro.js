document.addEventListener('DOMContentLoaded', () => {
  (() => {
    const svg = document.getElementById('toothSVG');
    if (!svg) return;

    // measure progress against a NON-transformed element
    const anchor = svg.closest('.tooth-wrap') || svg.parentElement || svg;

    const paths = Array.from(svg.querySelectorAll('path'));
    const baseStroke =
      getComputedStyle(document.documentElement)
        .getPropertyValue('--tooth-stroke').trim() || '#184b43';

    // Desktop motion params
    const START_X_VW = -32;   // left (vw)
    const START_Y_VH = -32;   // up (vh)
    const START_SCALE = 8;     // huge at start
    const END_SCALE = 1;     // normal at end
    const START_OPAC = 0.08;  // faint at start
    const END_OPAC = 1;

    // Delay the DRAW so it's "less drawn at start"
    // Only start drawing after 22% of the scroll window.
    const DRAW_START = 0.1;
    const DRAW_END = 0.98;

    // Stroke setup (outline only)
    const lengths = paths.map((p) => {
      const L = p.getTotalLength();
      p.style.fill = 'none';
      p.style.stroke = baseStroke;
      p.style.strokeWidth = '1';
      p.style.strokeLinecap = 'round';
      p.style.strokeLinejoin = 'round';
      p.style.strokeDasharray = L;
      p.style.strokeDashoffset = L;  // fully hidden
      p.style.transition = 'stroke .25s ease';
      return L;
    });

    const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (reduce) {
      paths.forEach(p => { p.style.strokeDashoffset = 0; p.style.fill = 'none'; });
      svg.style.transform = 'none';
      svg.style.opacity = String(END_OPAC);
      return;
    }

    // Mobile/desktop switch
    const mqMobile = window.matchMedia('(max-width: 999.98px)');
    let motionEnabled = !mqMobile.matches; // desktop = true
    if (motionEnabled) {
      svg.style.opacity = String(START_OPAC); // avoid flash
      // set initial transform once (in px for stability)
      const vw = window.innerWidth, vh = window.innerHeight;
      const tx0 = START_X_VW * vw / 100;
      const ty0 = START_Y_VH * vh / 100;
      svg.style.transform = `translate3d(${tx0}px, ${ty0}px,0) scale(${START_SCALE})`;
    } else {
      svg.style.opacity = String(END_OPAC);
      svg.style.transform = 'none';
    }

    // helpers
    const clamp = (n, a = 0, b = 1) => Math.max(a, Math.min(b, n));
    const lerp = (a, b, t) => a + (b - a) * t;
    const smooth = (t) => t * t * (3 - 2 * t);      // smoothstep (draw)
    const easeOut = (t) => 1 - Math.pow(1 - t, 3);  // motion

    function progressFor(el) {
      // Use ANCHOR (not the transformed svg) to avoid jitter/offset bugs
      const r = el.getBoundingClientRect();
      const vh = window.innerHeight || document.documentElement.clientHeight;
      const start = vh * 0.15;
      const end = r.height + vh * 0.60;
      const raw = (vh - r.top - start) / (end - start);
      return clamp(raw);
    }

    let ticking = false;

    function render() {
      const t = progressFor(anchor);
      const tm = easeOut(t); // motion/fade progression

      // Draw later in the scroll window
      const tRawDraw = clamp((t - DRAW_START) / (DRAW_END - DRAW_START));

      const MIN_DRAW = 0.05; // 15% drawn at start
      const td = MIN_DRAW + (1 - MIN_DRAW) * smooth(tRawDraw);


      // Draw stroke
      paths.forEach((p, i) => {
        p.style.strokeDashoffset = (1 - td) * lengths[i];
        p.style.fill = 'none';
      });

      // Motion only on desktop
      if (motionEnabled) {
        const vw = window.innerWidth, vh = window.innerHeight;
        const tx = lerp(START_X_VW * vw / 100, 0, tm);
        const ty = lerp(START_Y_VH * vh / 100, 0, tm);
        const sc = lerp(START_SCALE, END_SCALE, tm);
        const op = lerp(START_OPAC, END_OPAC, tm);
        svg.style.transform = `translate3d(${tx}px, ${ty}px, 0) scale(${sc})`;
        svg.style.opacity = String(op);
      } else {
        svg.style.transform = 'none';
        svg.style.opacity = String(END_OPAC);
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
      motionEnabled = !e.matches; // desktop when > 1000px
      if (!motionEnabled) {
        svg.style.transform = 'none';
        svg.style.opacity = String(END_OPAC);
      } else {
        svg.style.opacity = String(START_OPAC);
        // reset to start transform in px to avoid jump
        const vw = window.innerWidth, vh = window.innerHeight;
        const tx0 = START_X_VW * vw / 100;
        const ty0 = START_Y_VH * vh / 100;
        svg.style.transform = `translate3d(${tx0}px, ${ty0}px,0) scale(${START_SCALE})`;
      }
      onScroll();
    };
    if (mqMobile.addEventListener) mqMobile.addEventListener('change', handleMQ);
    else mqMobile.addListener(handleMQ);

    const io = new IntersectionObserver(([entry]) => {
      if (entry.isIntersecting) onScroll();
    }, { threshold: 0 });
    io.observe(anchor);

    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll, { passive: true });

    onScroll();
  })();
});
