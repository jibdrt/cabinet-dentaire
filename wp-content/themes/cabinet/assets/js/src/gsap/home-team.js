// // team-fade.js
// document.addEventListener('DOMContentLoaded', () => {
//   if (!window.gsap) return;
//   gsap.registerPlugin(ScrollTrigger);

//   const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
//   const cards  = gsap.utils.toArray('.cdcc__home__team .team__item');
//   if (!cards.length) return;

//   // initial state for all cards
//   gsap.set(cards, { autoAlpha: 0, y: 40 });

//   // batch: groups items that enter around the same time and animates with stagger
//   ScrollTrigger.batch(cards, {
//     start: 'top 80%',   // each card starts when its own top hits 80% of viewport
//     once: false,        // set true if you want to animate only the first time
//     interval: 0.08,     // how often to check for new entries
//     batchMax: 6,        // max items per batch (tune as you like)

//     onEnter: batch => gsap.to(batch, {
//       autoAlpha: 1,
//       y: 0,
//       duration: reduce ? 0.2 : 0.8,
//       ease: reduce ? 'none' : 'power2.out',
//       stagger: reduce ? 0.04 : 0.12,
//       overwrite: 'auto'
//     }),

//     // animate back when scrolling upward
//     onLeaveBack: batch => gsap.to(batch, {
//       autoAlpha: 0,
//       y: 40,
//       duration: reduce ? 0.15 : 0.5,
//       ease: reduce ? 'none' : 'power1.out',
//       stagger: reduce ? 0.03 : 0.08,
//       overwrite: 'auto'
//     })
//   });
// });
