document.addEventListener('DOMContentLoaded', () => {
    (function () {
        const grid = document.getElementById('cabinet-grid');
        if (!grid) return;

        // Masonry
        const msnry = new Masonry(grid, {
            itemSelector: '.grid__item',
            columnWidth: '.grid__sizer',
            gutter: '.grid__gutter',
            percentPosition: true,
            transitionDuration: '0.3s'
        });

        imagesLoaded(grid).on('progress', function () {
            msnry.layout();
        });

        // Lightbox
        baguetteBox.run('.gallery', {
            captions: true,
            buttons: 'auto',
            noScrollbars: true,
        });

        // After baguetteBox.run(...)
        (function enhanceLightboxUI() {
            const ensure = () => {
                const overlay = document.getElementById('baguetteBox-overlay');
                if (!overlay) return;

                const prev = document.getElementById('previous-button');
                const next = document.getElementById('next-button');
                const close = document.getElementById('close-button');
                if (!prev || !next || !close) return;

                // Add Iconify spans once
                const addIcon = (btn, icon) => {
                    if (!btn.querySelector('.iconify')) {
                        btn.textContent = ''; // remove default icon/bg
                        const span = document.createElement('span');
                        span.className = 'iconify';
                        span.setAttribute('data-icon', icon);
                        span.setAttribute('aria-hidden', 'true');
                        btn.appendChild(span);
                    }
                };
                addIcon(prev, 'tabler:chevron-left');
                addIcon(next, 'tabler:chevron-right');
                addIcon(close, 'tabler:x');

                // ARIA labels (for safety)
                prev.setAttribute('aria-label', 'Précédent');
                next.setAttribute('aria-label', 'Suivant');
                close.setAttribute('aria-label', 'Fermer');
            };

            // Run once overlay is created (first open)
            const mo = new MutationObserver(() => { ensure(); });
            mo.observe(document.body, { childList: true, subtree: true });

            // If overlay already exists (e.g. cached), run now too
            ensure();
        })();

    })()

});

