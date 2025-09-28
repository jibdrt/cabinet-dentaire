// WOW slice reveal for hero image
(() => {
    const MEDIA_SEL = '.cdcc__cabinet .cdcc__hero__media';
    const IMG_SEL = `${MEDIA_SEL} img`;

    function sliceReveal({
        columns = 10,          // number of vertical pieces
        dur = 0.9,             // per-slice duration
        stagger = 0.06,        // delay between slices
        ease = 'power3.out',
    } = {}) {
        const media = document.querySelector(MEDIA_SEL);
        const img = document.querySelector(IMG_SEL);
        if (!media || !img) return;

        // Respect reduced motion: just show image
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            img.style.opacity = 1;
            media.classList.add('is-ready');
            return;
        }

        const src = img.currentSrc || img.src;
        if (!src) return;

        const rect = media.getBoundingClientRect();
        const w = rect.width || media.clientWidth || img.naturalWidth || 1920;
        const h = rect.height || media.clientHeight || img.naturalHeight || 1080;

        // Ensure the base image starts hidden during the reveal
        img.style.opacity = '0';

        // Create overlay container
        const overlay = document.createElement('div');
        overlay.className = 'slice-reveal';
        media.appendChild(overlay);

        // Build slices
        const colW = 100 / columns;
        const slices = [];
        for (let i = 0; i < columns; i++) {
            const s = document.createElement('div');
            s.className = 'slice';
            // position & background cropping
            s.style.left = `${i * colW}%`;
            s.style.width = `${colW}%`;

            // background sizing to mimic object-fit: cover
            // compute cover background-size in px
            const imgRatio = img.naturalWidth && img.naturalHeight
                ? img.naturalWidth / img.naturalHeight
                : 16 / 9;
            const containerRatio = w / h;

            let bgW, bgH;
            if (imgRatio > containerRatio) {
                // image wider -> scale by height
                bgH = h;
                bgW = h * imgRatio;
            } else {
                // image taller -> scale by width
                bgW = w;
                bgH = w / imgRatio;
            }

            // background-size in px; browsers accept px even on absolutely positioned
            s.style.backgroundImage = `url("${src}")`;
            s.style.backgroundSize = `${bgW}px ${bgH}px`;

            // background-position to show the slice's strip
            const sliceWidth = w / columns;
            const sliceLeft = i * sliceWidth;
            const offsetX = (bgW - w) / 2;   // extra width added by "cover" scaling
            const posX = -(offsetX + sliceLeft);
            const posY = -(bgH - h) / 2;
            s.style.backgroundPosition = `${posX}px ${posY}px`;

            overlay.appendChild(s);
            slices.push(s);
        }

        // Animate slices alternating from top/bottom
        const fromTop = (i) => i % 2 === 0;
        slices.forEach((el, i) => {
            gsap.set(el, { yPercent: fromTop(i) ? -110 : 110, opacity: 0 });
        });

        const tl = gsap.timeline({
            defaults: { ease, duration: dur },
            onComplete: () => {
                // Reveal the real image, clean up overlay
                gsap.set(img, { opacity: 1 });
                overlay.remove();
                media.classList.add('is-ready'); // reuses your subtle zoom polish
            }
        });

        tl.to(slices, {
            yPercent: 0,
            opacity: 1,
            stagger: stagger
        });
    }

    // Kick it off when DOM is ready
    document.addEventListener('DOMContentLoaded', () => {
        // run immediately, or call sliceReveal() when your hero starts
        sliceReveal({
            columns: 12,
            dur: 0.8,
            stagger: 0.05,
            ease: 'power4.out'
        });
    });

    // If you prefer to start only after your loader finishes:
    // window.addEventListener('site-loader:done', () => sliceReveal(), { once: true });
})();
