document.addEventListener('DOMContentLoaded', function () {
    const containers = Array.from(document.querySelectorAll('.equipe__container .list'));
    if (!containers.length) return;

    // Init Isotope on each list
    const isos = containers.map((ul) => new Isotope(ul, {
        itemSelector: '.team__item',
        layoutMode: 'fitRows',
        percentPosition: true,
    }));

    // Filter bar
    const bar = document.querySelector('.team-filters');
    if (!bar) return;

    bar.addEventListener('click', (e) => {
        const btn = e.target.closest('button[data-filter]');
        if (!btn) return;

        // active state
        bar.querySelectorAll('button').forEach(b => b.classList.toggle('is-active', b === btn));

        const selector = btn.dataset.filter || '*';

        // Apply to both lists
        isos.forEach(iso => {
            if (selector === '*') {
                iso.arrange({ filter: '*' });
            } else {
                // Use attribute selector against each item
                iso.arrange({
                    filter: (itemElem) => itemElem.matches(selector)
                });
            }
        });
    });

    // Optional: trigger default filter (Tous)
    const active = bar.querySelector('button.is-active');
    if (active) active.click();
});