document.addEventListener('DOMContentLoaded', function () {
    // assets/js/gsap-animations.js
    (() => {
        // Respect reduced motion
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

        // Load GSAP + ScrollTrigger (assumes they're enqueued)
        if (!window.gsap) return;
        const { gsap } = window;
        if (gsap && gsap.registerPlugin && window.ScrollTrigger) {
            gsap.registerPlugin(window.ScrollTrigger);
        }

        // ---------- Helpers
        const q = (sel, ctx = document) => ctx.querySelector(sel);
        const qa = (sel, ctx = document) => Array.from(ctx.querySelectorAll(sel));

        // Generic one-shot reveal for a group
        function revealOnce(container, targets, {
            y = 24,
            duration = 0.9,
            ease = 'power3.out',
            stagger = 0.08,
            start = 'top 85%',
            end = 'bottom 20%',
            markers = false,
        } = {}) {
            const els = typeof targets === 'string' ? qa(targets, container) : targets;
            if (!els.length) return;
            gsap.set(els, { autoAlpha: 0, y });
            gsap.to(els, {
                autoAlpha: 1,
                y: 0,
                duration,
                ease,
                stagger,
                scrollTrigger: {
                    trigger: container,
                    start,
                    end,
                    toggleActions: 'play none none none',
                    markers,
                }
            });
        }

        // Reveal each item in a list (per-item timeline for inner bits)
        function revealItemsOnce(listSelector, {
            item = '.item',
            img = '.col__image img',
            title = '.item__title',
            desc = '.item__description',
            cta = '.btn',
            start = 'top 85%',
            markers = false
        } = {}) {
            qa(listSelector).forEach(list => {
                qa(item, list).forEach(el => {
                    const tl = gsap.timeline({
                        scrollTrigger: {
                            trigger: el,
                            start,
                            toggleActions: 'play none none none',
                            markers,
                        }
                    });
                    const i = q(img, el);
                    const t = q(title, el);
                    const d = q(desc, el);
                    const b = q(cta, el);

                    gsap.set(el, { autoAlpha: 0, y: 24 });
                    if (i) gsap.set(i, { autoAlpha: 0, scale: 1.05, y: 8 });

                    tl.to(el, { autoAlpha: 1, y: 0, duration: 0.8, ease: 'power3.out' })
                        .to(i, { autoAlpha: 1, scale: 1, y: 0, duration: 0.9, ease: 'power3.out' }, '-=0.6')
                        .to(t, { autoAlpha: 1, y: 0, duration: 0.5, ease: 'power2.out' }, '-=0.55')
                        .to(d, { autoAlpha: 1, y: 0, duration: 0.6, ease: 'power2.out' }, '-=0.5')
                        .to(b, { autoAlpha: 1, y: 0, duration: 0.45, ease: 'power2.out' }, '-=0.5');
                });
            });
        }

        // ---------- Page sections

        // HERO intro: cascade on page load (no scroll trigger), images untouched
(() => {
  const section = q('.cdcc__home__hero');
  if (!section) return;

  const content  = q('.cdcc__home__hero__inner .content .inner__content', section) || q('.inner__content', section);
  if (!content) return;

  const titleWrap = q('.inner__content__title', content);
  const h1        = titleWrap?.querySelector('h1');
  const subline   = titleWrap?.querySelector('span');
  const hr        = q('.inner__content__separator', content);
  const subtitle  = q('.inner__content__subtitle', content);
  const buttons   = qa('.btn.btn--hero', content);
  const dots      = qa('.btn__dot', content);
  const icons     = qa('.btn__icon', content);
  const svg       = section.querySelector('svg');

  const isSmall = window.matchMedia('(max-width: 999px)').matches;

  // Reduced motion: show immediately
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    gsap.set([h1, subline, hr, subtitle, buttons, dots, icons, svg], { clearProps: 'all' });
    return;
  }

  // Initial states
  if (h1)       gsap.set(h1,       { autoAlpha: 0, y: 28 });
  if (subline)  gsap.set(subline,  { autoAlpha: 0, y: 24 });
  if (hr)       gsap.set(hr,       { autoAlpha: 0, scaleX: 0, transformOrigin: 'left center' });
  if (subtitle) gsap.set(subtitle, { autoAlpha: 0, y: 22 });

  if (buttons.length) {
    gsap.set(buttons, { autoAlpha: 0, y: 18 });
    if (dots.length)  gsap.set(dots,  { scale: 0, transformOrigin: '50% 50%' });
    if (icons.length) gsap.set(icons, { x: -8 });
  }

  // Hide SVG only on small screens so we can reveal it later
  if (svg) {
    if (isSmall) {
      gsap.set(svg, { autoAlpha: 0 });
    } else {
      gsap.set(svg, { clearProps: 'opacity,visibility' }); // no delay ≥1000px
    }
  }

  const tl = gsap.timeline({ defaults: { ease: 'power3.out' }, delay: 0.05 });

  if (h1)       tl.to(h1,       { autoAlpha: 1, y: 0, duration: 0.8 });
  if (subline)  tl.to(subline,  { autoAlpha: 1, y: 0, duration: 0.6 }, '-=0.45');
  if (hr)       tl.to(hr,       { autoAlpha: 1, scaleX: 1, duration: 0.45, ease: 'power2.out' }, '-=0.25');
  if (subtitle) tl.to(subtitle, { autoAlpha: 1, y: 0, duration: 0.6 }, '-=0.05');

  if (buttons.length) {
    tl.to(buttons, { autoAlpha: 1, y: 0, duration: 0.55, ease: 'power2.out', stagger: 0.12 }, '-=0.05')
      .to(dots,    { scale: 1, duration: 0.35, ease: 'back.out(2)', stagger: 0.12 }, '<')
      .to(icons,   { x: 0, duration: 0.35, ease: 'power2.out', stagger: 0.12 }, '<+0.05')
      .add(() => { gsap.set(icons, { clearProps: 'opacity,visibility' }); });
  }

  // 👉 Reveal SVG only under 1000px (after buttons)
  if (svg && isSmall) {
    tl.to(svg, { autoAlpha: 1, duration: 0.45, ease: 'power2.out' }, '>');
    // or with a tiny lift:
    // tl.fromTo(svg, { autoAlpha: 0, y: 8 }, { autoAlpha: 1, y: 0, duration: 0.45, ease: 'power2.out' }, '>');
  }

  document.addEventListener('iconify-icon-updated', () => {
    gsap.set(icons, { clearProps: 'opacity,visibility' });
  }, { once: true });
})();




        // INTRO
        (() => {
            const section = q('.cdcc__home__intro');
            if (!section) return;
            revealOnce(section, [
                q('.cdcc__home__intro__title', section),
                q('.cdcc__home__intro__content', section)
            ]);
        })();

        // VIDEO
        (() => {
            const section = q('.cdcc__home__video');
            if (!section) return;
            revealOnce(section, [
                q('.cdcc__home__video__frame', section),
                q('.cdcc__home__video .btn', section)
            ]);
        })();

        // SOINS — bg fade on enter, lines scrub across whole section, items reveal
        (() => {



            const section = q('.cdcc__home__soins');
            if (!section) return;

            const ROOT = document.documentElement;
            const INTRO = document.querySelector('.cdcc__home__intro__inner');

            gsap.set(ROOT, { '--page-bg': '#ffffff' });
            gsap.set(INTRO, { '--intro-fg': '#3D4C51' }); // start dark text

            const tl = gsap.timeline({
                scrollTrigger: {
                    trigger: section,
                    start: 'top bottom',
                    end: 'bottom top',
                    scrub: 0.6,
                    invalidateOnRefresh: true,
                }
            });

            tl.to({}, { duration: 0.08 }); // small hold

            // label so bg + text change together
            tl.add('bgTo333');

            // 1) #fff → #333
            tl.to(ROOT, { '--page-bg': '#333333', duration: 0.18, ease: 'power1.out' }, 'bgTo333');

            // 👉 switch intro text to white at the same moment
            tl.to(INTRO, { '--intro-fg': '#ffffff', duration: 0.18, ease: 'power1.out' }, 'bgTo333');

            // 2) #333 → #3D4C51 (keep text white)
            tl.to(ROOT, { '--page-bg': '#3D4C51', duration: 0.74, ease: 'none' });

            // Reduced motion
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                ScrollTrigger.getAll().forEach(st => st.kill());
                gsap.set(ROOT, { '--page-bg': '#3D4C51' });
                gsap.set(INTRO, { '--intro-fg': '#ffffff' });
            }


            // Lines scrub (unchanged)
            const paths = qa('.soins-lines .lines path', section);
            if (paths.length) {
                paths.forEach(p => {
                    const len = p.getTotalLength();
                    gsap.set(p, { strokeDasharray: len, strokeDashoffset: len });
                });

                const ltl = gsap.timeline({
                    defaults: { ease: 'none' },
                    scrollTrigger: {
                        trigger: section,
                        start: 'top 90%',
                        end: 'bottom 10%',
                        scrub: true,
                    }
                });

                paths.forEach((p, i) => {
                    const len = p.getTotalLength();
                    ltl.fromTo(p, { strokeDashoffset: len }, { strokeDashoffset: 0 }, i * 0.1);
                });
                ltl.to(paths, { stroke: 'rgba(255,255,255,0.45)' }, 0.25);
            }
        })();



        // TEAM — center-center trigger feel
        (() => {
            const section = q('.cdcc__home__team');
            if (!section) return;
            const title = q('.cdcc__home__team__title .title', section);
            const subtitle = q('.cdcc__home__team__title .subtitle', section);
            const btn = q('.cdcc__home__team__title .btn--team', section);
            const imageBox = q('.cdcc__home__team__content .image', section);

            const els = [title, subtitle, btn, imageBox].filter(Boolean);
            gsap.set(els, { autoAlpha: 0, y: 24 });

            gsap.to(els, {
                autoAlpha: 1,
                y: 0,
                duration: 1.0,
                ease: 'power3.out',
                stagger: 0.15,
                scrollTrigger: {
                    trigger: section,
                    start: 'top 85%',
                    toggleActions: 'play none none none'
                }
            });
        })();

        // URGENCES
        (() => {
            const section = q('.cdcc__home__urgences');
            if (!section) return;
            const left = q('.inner .col .image', section);
            const right = q('.inner .col .content', section);

            gsap.set([left, right], { autoAlpha: 0, y: 24 });
            gsap.to([left, right], {
                autoAlpha: 1,
                y: 0,
                duration: 0.9,
                ease: 'power3.out',
                stagger: 0.15,
                scrollTrigger: { trigger: section, start: 'top 85%', toggleActions: 'play none none none' }
            });
        })();

        // ACCES (map)
        (() => {
            const section = q('.cdcc__home__acces');
            if (!section) return;
            revealOnce(section, q('#map', section));
        })();

        // HOURS
        (() => {
            const section = q('.cdcc__home__hours');
            if (!section) return;
            revealOnce(section, [
                q('.cdcc__home__hours__title', section),
                q('.cdcc__home__hours__content', section)
            ]);
        })();

        // Recompute after heavy assets load
        window.addEventListener('load', () => window.ScrollTrigger && window.ScrollTrigger.refresh());
    })();

});