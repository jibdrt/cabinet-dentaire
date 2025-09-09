document.addEventListener('DOMContentLoaded', function () {
    new Splide('.hero-splide', {
        type: 'fade',
        perPage: 1,
        autoplay: true,
        rewind: true,
        arrows: false,
        pagination: true,
    }).mount();
});
