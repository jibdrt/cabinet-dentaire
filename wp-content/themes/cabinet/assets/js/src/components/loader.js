document.addEventListener('DOMContentLoaded', () => {
    // Arm the page to hide hero content preemptively
    document.documentElement.classList.add('hero-armed');

    const loader = document.getElementById('site-loader');
    if (!loader) return;

    const svg = loader.querySelector('.logo-draw');
    if (!svg) return;

    // cookie helpers
    const COOKIE_NAME = 'introSeen';
    const COOKIE_TTL_S = 10 * 60;
    const getCookie = (n) => document.cookie.split('; ').find(r => r.startsWith(n + '='))?.split('=')[1];
    const setCookie = (n, v, maxAge) => document.cookie = `${n}=${encodeURIComponent(v)}; path=/; samesite=lax; max-age=${maxAge}`;
    const fireDone = () => window.dispatchEvent(new CustomEvent('site-loader:done'));

    // Skip animation if cookie present
    if (getCookie(COOKIE_NAME)) {
        loader.classList.add('is-done');
        setTimeout(() => { try { loader.remove(); } catch { }; fireDone(); }, 500);
        return;
    }

    const mark = svg.querySelectorAll('#logoMark .draw');
    const text = svg.querySelectorAll('#logoText .draw');
    const subtext = svg.querySelectorAll('#logoSubText .draw');

    const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // Your fast settings
    const SECONDS_PER_PX = 0.00002;
    const STAGGER = 0.015;
    const MIN_DUR = 0.02;
    const END_HOLD_MS = 120;

    const animateGroup = (nodes, startDelayS = 0) => {
        let endMs = startDelayS * 1000;
        nodes.forEach((el, i) => {
            let L = 400;
            try { L = el.getTotalLength(); } catch { }
            el.style.strokeDasharray = String(L);
            el.style.strokeDashoffset = String(L);
            if (!prefersReduced) {
                const dur = Math.max(MIN_DUR, L * SECONDS_PER_PX);
                const delay = startDelayS + i * STAGGER;
                el.style.animation = `dash ${dur}s ease forwards`;
                el.style.animationDelay = `${delay}s`;
                endMs = Math.max(endMs, (delay + dur) * 1000);
            } else {
                el.style.strokeDashoffset = '0';
            }
        });
        return endMs;
    };

    // Phase 1 — mark
    svg.classList.add('show-mark');
    const markEnd = animateGroup(mark, 0);

    const afterMark = () => {
        svg.classList.add('mark-filled');
        // Phase 2 — text
        svg.classList.add('show-text');
        const textEnd = animateGroup(text, markEnd / 1000);

        const afterText = () => {
            svg.classList.add('text-filled');
            // Phase 3 — subtext
            svg.classList.add('show-sub');
            const subEnd = animateGroup(subtext, textEnd / 1000);

            const afterSub = () => {
                svg.classList.add('subtext-filled');
                // Fade out
                setTimeout(() => {
                    loader.classList.add('is-done');
                    setTimeout(() => {
                        try { loader.remove(); } catch { }
                        setCookie(COOKIE_NAME, '1', COOKIE_TTL_S);
                        fireDone();
                    }, 500); // match CSS transition
                }, END_HOLD_MS);
            };

            if (!prefersReduced && subEnd > 0) setTimeout(afterSub, subEnd);
            else afterSub();
        };

        if (!prefersReduced && textEnd > 0) setTimeout(afterText, textEnd);
        else afterText();
    };

    if (!prefersReduced && markEnd > 0) setTimeout(afterMark, markEnd);
    else afterMark();

    (function () {
        function armReady() {
            // Only flip if we were actually hiding the page
            if (document.documentElement.classList.contains('app-loading')) {
                document.documentElement.classList.remove('app-loading');
                document.documentElement.classList.add('app-ready');
            }
        }

        window.addEventListener('site-loader:done', armReady, { once: true });

        // Safety: if something breaks, don’t leave users with a blank page
        setTimeout(armReady, 8000);
    })();

});
