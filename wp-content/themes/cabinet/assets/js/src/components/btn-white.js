// document.addEventListener('DOMContentLoaded', () => {
//     const btns = document.querySelectorAll('.btn.btn--soin');

//     btns.forEach(btn => {
//         // pointer enters or moves: update CSS vars for ripple origin
//         const setPos = (e) => {
//             const r = btn.getBoundingClientRect();
//             const x = (e.clientX ?? (e.touches?.[0]?.clientX || 0)) - r.left;
//             const y = (e.clientY ?? (e.touches?.[0]?.clientY || 0)) - r.top;
//             btn.style.setProperty('--mx', x + 'px');
//             btn.style.setProperty('--my', y + 'px');
//         };

//         btn.addEventListener('pointerenter', setPos);
//         btn.addEventListener('pointermove', setPos);

//         // on leave, let it fade back by shrinking a bit
//         btn.addEventListener('pointerleave', () => {
//             btn.style.removeProperty('--mx');
//             btn.style.removeProperty('--my');
//         });
//     });
// });
