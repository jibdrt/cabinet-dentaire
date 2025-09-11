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

        // HERO
        (() => {
            const section = q('.cdcc__home__hero');
            if (!section) return;
            const title = q('.inner__content__title', section);
            const subtitle = q('.inner__content__subtitle', section);
            const btn = q('.hero__btn', section);

            gsap.set([title, subtitle, btn], { autoAlpha: 0, y: 24 });
            const tl = gsap.timeline({
                scrollTrigger: { trigger: section, start: 'top 85%', toggleActions: 'play none none none' }
            });
            tl.to(title, { autoAlpha: 1, y: 0, duration: 0.8, ease: 'power3.out' })
                .to(q('.inner__content__separator', section), { autoAlpha: 1, scaleX: 1, duration: 0.5, ease: 'power2.out' }, '-=0.5')
                .to(subtitle, { autoAlpha: 1, y: 0, duration: 0.7, ease: 'power2.out' }, '-=0.3')
                .to(btn, { autoAlpha: 1, y: 0, duration: 0.5, ease: 'power2.out' }, '-=0.4');
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

            // BG fade (non-scrub): white -> greendark when title enters
            const title = q('.cdcc__home__soins__title', section);
            gsap.set(section, { backgroundColor: '#ffffff' });
            gsap.to(section, {
                backgroundColor: '#3D4C51',
                duration: 0.8,
                ease: 'power2.out',
                scrollTrigger: {
                    trigger: title || section,
                    start: 'top 85%',
                    toggleActions: 'play none none reverse'
                }
            });

            // Lines scrub
            const paths = qa('.soins-lines .lines path', section);
            if (paths.length) {
                paths.forEach(p => {
                    const len = p.getTotalLength();
                    gsap.set(p, { strokeDasharray: len, strokeDashoffset: len });
                });

                const tl = gsap.timeline({
                    defaults: { ease: 'none' },
                    scrollTrigger: {
                        trigger: section,
                        start: 'top 90%',
                        end: 'bottom 10%',
                        scrub: true,
                        // markers: true,
                    }
                });

                paths.forEach((p, i) => {
                    const len = p.getTotalLength();
                    tl.fromTo(p, { strokeDashoffset: len }, { strokeDashoffset: 0 }, i * 0.1);
                });
                tl.to(paths, { stroke: 'rgba(255,255,255,0.45)' }, 0.25);
            }

            // Items
            revealItemsOnce('.cdcc__home__soins__content .list');
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