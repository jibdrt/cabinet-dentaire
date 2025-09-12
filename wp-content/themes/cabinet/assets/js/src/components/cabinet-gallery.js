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
    })()

});