document.addEventListener('DOMContentLoaded', function () {
    // ---------- CONFIG ----------
    const HOME_PATHS = ["/", "/index.php"];        // adjust if your home is under /fr/ etc.
    const MOBILE_BP = 1000;                        // same breakpoint as your code
    const SCROLL_OFFSET = () => document.querySelector('.cdcc__header')?.offsetHeight || 0;

    // If you prefer, hardcode home URL:
    // const HOME_URL = "https://example.com/"; 
    const HOME_URL = `${window.location.origin}/`;

    // ---------- HELPERS ----------
    function isHome() {
        return HOME_PATHS.includes(window.location.pathname);
    }

    function smoothScrollTo(targetEl) {
        if (!targetEl) return;
        const headerOffset = SCROLL_OFFSET();
        const rect = targetEl.getBoundingClientRect();
        const targetY = window.pageYOffset + rect.top - headerOffset;

        window.scrollTo({ top: targetY, behavior: "smooth" });
    }

    function getHashFromHref(href) {
        try {
            // Handles absolute, relative, and hash-only hrefs
            const url = new URL(href, window.location.origin);
            return url.hash || "";
        } catch {
            return href.startsWith("#") ? href : "";
        }
    }

    // ---------- ACTIVE STATE (client-side) ----------
    const anchorMenuLinks = Array.from(document.querySelectorAll('.cdcc__header a[href*="#"]'))
        .filter(a => getHashFromHref(a.getAttribute('href')));

    function setActiveLinkByHash(hash) {
        anchorMenuLinks.forEach(a => a.classList.remove('is-active'));
        if (!hash) return;
        const match = anchorMenuLinks.find(a => getHashFromHref(a.getAttribute('href')) === hash);
        if (match) match.classList.add('is-active');
    }

    // Optional: update active on scroll (basic)
    // You can replace with IntersectionObserver for more accuracy.
    window.addEventListener('hashchange', () => setActiveLinkByHash(location.hash));

    // ---------- CLICK HANDLER ----------
    anchorMenuLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            const href = link.getAttribute('href') || "";
            const hash = getHashFromHref(href); // e.g. "#soins"
            if (!hash || hash === "#") return;

            const onMobile = window.innerWidth < MOBILE_BP;

            if (isHome()) {
                // Same-page smooth scroll
                e.preventDefault();

                // Close mobile menu first
                if (onMobile && document.querySelector('.cdcc__header')?.classList.contains('is-menu-open')) {
                    closeMenu();
                    // wait a tick for the menu to close visually
                    setTimeout(() => {
                        const targetEl = document.querySelector(hash);
                        smoothScrollTo(targetEl);
                        history.replaceState(null, "", hash); // update URL without jumping
                        setActiveLinkByHash(hash);
                    }, 200);
                } else {
                    const targetEl = document.querySelector(hash);
                    smoothScrollTo(targetEl);
                    history.replaceState(null, "", hash);
                    setActiveLinkByHash(hash);
                }
            } else {
                // Not on home → go to home + hash
                // Don’t mutate link href globally (that caused "all current" styling).
                e.preventDefault();
                window.location.href = HOME_URL.replace(/\/+$/, "/") + hash.slice(1) ? `${HOME_URL}${hash}` : HOME_URL;
            }
        });
    });

    // ---------- HANDLE HASH ON PAGE LOAD (arriving from another page) ----------
    window.addEventListener('DOMContentLoaded', () => {
        if (!isHome()) return;
        if (!location.hash) return;
        const targetEl = document.querySelector(location.hash);
        if (!targetEl) return;

        // If the browser already jumped, correct for header offset smoothly
        setTimeout(() => {
            smoothScrollTo(targetEl);
            setActiveLinkByHash(location.hash);
        }, 150);
    });
});