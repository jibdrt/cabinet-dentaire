// document.addEventListener('DOMContentLoaded', function () {
//   if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

//   gsap.registerPlugin(ScrollTrigger);

//   const section = document.querySelector('.cdcc__home__soins');
//   const title   = section.querySelector('.cdcc__home__soins__title');
//   const paths   = gsap.utils.toArray('.cdcc__home__soins .soins-lines .lines path');

//   // --- BG: fade from white -> greendark when the title reaches viewport (non-scrub) ---
//   gsap.set(section, { backgroundColor: '#ffffff' });
//   gsap.to(section, {
//     backgroundColor: '#3D4C51',
//     duration: 0.8,
//     ease: 'power2.out',
//     scrollTrigger: {
//       trigger: title,         // tie to visual entry point
//       start: 'top 85%',       // when the title approaches viewport
//       toggleActions: 'play none none reverse'
//       // use 'play none none none' if you don't want reverse
//     }
//   });

//   // --- Lines: prepare for draw animation ---
//   paths.forEach(p => {
//     const len = p.getTotalLength();
//     gsap.set(p, { strokeDasharray: len, strokeDashoffset: len });
//   });

//   // --- Lines: scrubbed over the whole section (slow + reversible) ---
//   const linesTL = gsap.timeline({
//     defaults: { ease: 'none' },
//     scrollTrigger: {
//       trigger: section,
//       start: 'top 90%',      // start near section entry
//       end:   'bottom 10%',   // finish near section exit (slow over whole height)
//       scrub: true,           // progress = scroll %, reverses on scroll up
//       // markers: true,
//     }
//   });

//   // draw with gentle stagger so they overlap but last across the section
//   paths.forEach((p, i) => {
//     const len = p.getTotalLength();
//     linesTL.fromTo(p, { strokeDashoffset: len }, { strokeDashoffset: 0 }, i * 0.1);
//   });

//   // Optional: improve contrast as bg darkens (happens early in the scrub)
//   linesTL.to(paths, { stroke: 'rgba(255,255,255,0.45)' }, 0.25);

//   // Recalculate after images/fonts
//   window.addEventListener('load', () => ScrollTrigger.refresh());
// });
