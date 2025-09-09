// video.js
document.addEventListener('DOMContentLoaded', () => {
  if (!window.gsap) return;
  gsap.registerPlugin(ScrollTrigger);

  const reduce  = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const section = document.querySelector('.cdcc__home__video');
  if (!section) return;

  const frame = section.querySelector('.cdcc__home__video__frame');
  const video = section.querySelector('.cdcc__home__video__media');
  if (!frame || !video) return;

  // initial state (all via GSAP as requested)
  gsap.set(frame, { borderRadius: 20 });
  gsap.set(video, { scale: 0.7, autoAlpha: 1, force3D: true });

  const tl = gsap.timeline({
    defaults: { ease: 'power2.out' },
    scrollTrigger: {
      trigger: section,
      start: 'top 80%',     // when section enters viewport
      end: 'bottom top',    // until it leaves
      scrub: reduce ? true : 0.8,
      invalidateOnRefresh: true
    }
  });

  // zoom video
  tl.to(video, { scale: 1.15 }, 0);

  // flatten corners on the mask wrapper
  tl.to(frame, { borderRadius: 0 }, 0);
});
