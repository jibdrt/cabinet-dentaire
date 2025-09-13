(function () {
    const header = document.querySelector('.cdcc__header');
    if (!header) return;

    const burger = header.querySelector('.cdcc__header___burger');
    const closeBtn = header.querySelector('.cdcc__header___close');
    const mobile = header.querySelector('#mobileMenu');
    const linksToClose = header.querySelectorAll('.cdcc__header___mobileMenu a');

    function openMenu() {
        header.classList.add('is-menu-open');
        burger.setAttribute('aria-expanded', 'true');
        mobile.setAttribute('aria-hidden', 'false');
        document.documentElement.style.overflow = 'hidden';
    }

    function closeMenu() {
        header.classList.remove('is-menu-open');
        burger.setAttribute('aria-expanded', 'false');
        mobile.setAttribute('aria-hidden', 'true');
        document.documentElement.style.overflow = '';
    }

    burger?.addEventListener('click', openMenu);
    closeBtn?.addEventListener('click', closeMenu);
    linksToClose.forEach(a => a.addEventListener('click', closeMenu));

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && header.classList.contains('is-menu-open')) {
            closeMenu();
        }
    });


    if (window.innerWidth > 1000) {
        if (!header) return;

        let lastScroll = 0;
        const threshold = 10;      // don’t react to tiny jitters
        const hideOffset = -header.offsetHeight; // how far to move up

        window.addEventListener("scroll", () => {
            const currentScroll = window.pageYOffset || document.documentElement.scrollTop;

            if (Math.abs(currentScroll - lastScroll) < threshold) return;

            if (currentScroll > lastScroll && currentScroll > header.offsetHeight) {
                // scrolling down
                header.style.transform = `translateY(${hideOffset}px)`;
                header.classList.add("is-compact");
            } else {
                // scrolling up
                header.style.transform = "translateY(0)";
                if (currentScroll <= 10) {
                    header.classList.remove("is-compact");
                }
            }

            lastScroll = currentScroll;
        });

    }

    // Gestion des ancres #soins, #hours, etc.
    const anchorLinks = document.querySelectorAll('a[href^="#"]');

    anchorLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            const targetId = link.getAttribute('href'); // ex. "#soins"
            if (!targetId || targetId === "#") return;

            // Fermer le menu si mobile
            if (window.innerWidth < 1000 && header.classList.contains('is-menu-open')) {
                closeMenu();
            }

            // Vérifier si on est sur la homepage
            const isHome = window.location.pathname === "/" || window.location.pathname === "/index.php";

            if (isHome) {
                // Empêcher le comportement par défaut
                e.preventDefault();

                // Cibler l’ancre
                const targetEl = document.querySelector(targetId);
                if (targetEl) {
                    targetEl.scrollIntoView({ behavior: "smooth" });
                }
            } else {
                // Rediriger vers la home avec le hash
                link.setAttribute('href', `/${targetId}`);
            }
        });
    });


})();
