document.addEventListener('DOMContentLoaded', () => {
  const section = document.getElementById('toothDraw');
  if (!section) return;

  const svg = section.querySelector('svg');
  if (!svg) return;

  const shapes = Array.from(
    svg.querySelectorAll('path,line,polyline,polygon,circle,rect,ellipse')
  ).filter(el => typeof el.getTotalLength === 'function');
  if (!shapes.length) return;

  const baseStroke =
    getComputedStyle(document.documentElement)
      .getPropertyValue('--tooth-stroke').trim() || '#CBAE70';

  const lengths = shapes.map(el => {
    const L = el.getTotalLength();
    el.style.fill = 'none';
    el.style.stroke = baseStroke;
    if (!el.getAttribute('stroke-width')) el.style.strokeWidth = '1.5';
    el.style.strokeDasharray = L;
    el.style.strokeDashoffset = L;
    el.style.vectorEffect = 'non-scaling-stroke';
    return L;
  });

  // 👉 NEW: static mode for small screens OR reduced motion
  const isSmall = window.matchMedia('(max-width: 999px)').matches;
  const prefersReduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (isSmall || prefersReduce) {
    shapes.forEach(el => { el.style.strokeDashoffset = 0; });
    return; // no interaction below 1000px
  }

  // --- existing interactive logic below ---

  const cum = [];
  let total = 0;
  for (let i = 0; i < lengths.length; i++) { cum[i] = total; total += lengths[i]; }

  const START_VH = 0.7;
  const END_VH = 0.85;
  const DEAD_ZONE = 0.0;
  const EASE_POWER = 3;
  const DURATION_MULTIPLIER = 2;
  const START_PERCENT = 0.18;

  function progressFor(el) {
    const r = el.getBoundingClientRect();
    const vh = window.innerHeight || document.documentElement.clientHeight;
    const start = vh * START_VH;
    const baseEnd = r.height + vh * (1 - END_VH);
    const end = start + (baseEnd - start) * DURATION_MULTIPLIER;
    const raw = (vh - r.top - start) / (end - start);
    return Math.max(0, Math.min(1, raw));
  }

  let ticking = false;
  function render() {
    let t = progressFor(section);
    if (DEAD_ZONE > 0) {
      t = (t - DEAD_ZONE) / (1 - DEAD_ZONE);
      t = Math.max(0, Math.min(1, t));
    }
    const eased = Math.pow(t, EASE_POWER);
    const s = total * (START_PERCENT + (1 - START_PERCENT) * eased);

    shapes.forEach((el, i) => {
      const L = lengths[i];
      const a = cum[i];
      const b = a + L;
      let offset;
      if (s <= a) offset = L;
      else if (s >= b) offset = 0;
      else offset = L - (s - a);
      el.style.strokeDashoffset = offset;
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
  io.observe(section);

  window.addEventListener('scroll', onScroll, { passive: true });
  window.addEventListener('resize', onScroll, { passive: true });

  onScroll();
});
