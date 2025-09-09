// intro.js
document.addEventListener('DOMContentLoaded', () => {
    if (!window.gsap) return;
    gsap.registerPlugin(ScrollTrigger);

    const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const section = document.querySelector('.cdcc__home__intro');
    if (!section) return;

    const inner = section.querySelector('.cdcc__home__intro__inner');
    const title = section.querySelector('.cdcc__home__intro__title');
    const body = section.querySelector('.cdcc__home__intro__content');

    /* ---- 1) Smooth entrance on first reveal ---- */
    gsap.from([title, body, section], {
        y: 24,
        autoAlpha: 0,
        duration: reduce ? 0.2 : 0.9,
        ease: reduce ? 'none' : 'power2.out',
        stagger: reduce ? 0 : 0.1,
        scrollTrigger: {
            trigger: section,
            start: 'top 80%',
            once: true
        }
    });
});
