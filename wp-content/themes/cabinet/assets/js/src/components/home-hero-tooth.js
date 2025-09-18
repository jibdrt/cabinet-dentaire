// Sequential scroll-draw for the embedded SVG inside #intro-tooth
document.addEventListener('DOMContentLoaded', () => {
    const section = document.getElementById('toothDraw');
    if (!section) return;

    // Grab the FIRST inline SVG inside the section (the one you echoed)
    // If your file has an id on its <svg>, prefer: section.querySelector('#toothDraw')
    const svg = section.querySelector('svg');
    if (!svg) return;

    // Collect drawable shapes (must support getTotalLength)
    const shapes = Array.from(
        svg.querySelectorAll('path,line,polyline,polygon,circle,rect,ellipse')
    ).filter(el => typeof el.getTotalLength === 'function');

    if (!shapes.length) return;

    const baseStroke =
        getComputedStyle(document.documentElement)
            .getPropertyValue('--tooth-stroke').trim() || '#CBAE70';

    // Prime stroke + measure lengths
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

    // Cumulative map so a single pen position can be applied per shape
    const cum = [];
    let total = 0;
    for (let i = 0; i < lengths.length; i++) {
        cum[i] = total;
        total += lengths[i];
    }

    // Reduced motion → reveal immediately
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        shapes.forEach(el => { el.style.strokeDashoffset = 0; });
        return;
    }

    // === Tweakables ===
    const START_VH   = 0.7;   // begin drawing when section top reaches 70% of viewport height
    const END_VH     = 0.85;  // finish baseline when bottom crosses 85% of viewport height
    const DEAD_ZONE  = 0.0;   // 0–1 portion of progress ignored after start (hard delay)
    const EASE_POWER = 3;     // ease-in strength (2–4 works well)
    const DURATION_MULTIPLIER = 2; // Bigger = slower
    const START_PERCENT = 0.18;    // 🔥 0–1: start as if 25% is already drawn
    // ===================

    // Scroll progress for the whole section (later start + extended finish)
    function progressFor(el) {
        const r  = el.getBoundingClientRect();
        const vh = window.innerHeight || document.documentElement.clientHeight;

        // When the top crosses this line, we begin
        const start = vh * START_VH;

        // Baseline finish distance
        const baseEnd = r.height + vh * (1 - END_VH);

        // Virtually "stretch" the distance needed to complete the draw
        const end = start + (baseEnd - start) * DURATION_MULTIPLIER;

        const raw = (vh - r.top - start) / (end - start);
        return Math.max(0, Math.min(1, raw));
    }

    let ticking = false;
    function render() {
        // base linear progress
        let t = progressFor(section);

        // optional dead-zone to make the first portion do nothing at all
        if (DEAD_ZONE > 0) {
            t = (t - DEAD_ZONE) / (1 - DEAD_ZONE);
            t = Math.max(0, Math.min(1, t));
        }

        // ease-in so early scroll draws much less
        const eased = Math.pow(t, EASE_POWER);

        // Start partially drawn:
        // s goes from START_PERCENT*total -> total as eased goes 0 -> 1
        const s = total * (START_PERCENT + (1 - START_PERCENT) * eased);

        shapes.forEach((el, i) => {
            const L = lengths[i];
            const a = cum[i];        // start pos
            const b = a + L;         // end pos

            let offset;
            if (s <= a) offset = L;              // not started (for later shapes)
            else if (s >= b) offset = 0;         // finished
            else offset = L - (s - a);           // partial
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

    // Only work while the section is in view
    const io = new IntersectionObserver((entries) => {
        if (entries[0].isIntersecting) onScroll();
    });
    io.observe(section);

    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll, { passive: true });

    // initial paint
    onScroll();
});
