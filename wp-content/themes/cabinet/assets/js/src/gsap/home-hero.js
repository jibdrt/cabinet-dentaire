// // hero.js
// document.addEventListener("DOMContentLoaded", () => {
//   if (!window.gsap) return;
//   const reduce = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

//   const heroContent = document.querySelector(".cdcc__home__hero .inner__content");
//   const heroImage   = document.querySelector(".cdcc__home__hero .inner__media__image img");

//   if (!heroContent) return;

//   // --- Animate text blocks ---
//   const blocks = [
//     heroContent.querySelector(".inner__content__title h1"),
//     heroContent.querySelector(".inner__content__title span"),
//     heroContent.querySelector(".inner__content__separator"),
//     heroContent.querySelector(".inner__content__subtitle"),
//     heroContent.querySelector(".hero__btn")
//   ].filter(Boolean);

//   gsap.set(blocks, { autoAlpha: 0, y: 30 });

//   gsap.to(blocks, {
//     autoAlpha: 1,
//     y: 0,
//     duration: reduce ? 0.2 : 0.8,
//     ease: reduce ? "none" : "power3.out",
//     stagger: reduce ? 0 : 0.25, // little delay per block
//     delay: 0.3
//   });

//   // --- Animate hero image ---
//   if (heroImage) {
//     gsap.set(heroImage, { autoAlpha: 0, x: 60 }); // start left & invisible
//     gsap.to(heroImage, {
//       autoAlpha: 1,
//       x: 0,
//       duration: reduce ? 0.2 : 1,
//       ease: reduce ? "none" : "power3.out",
//       delay: reduce ? 0.1 : 0.4 // wait until after text starts animating
//     });
//   }
// });
