(function () {
  const modal = document.getElementById('teamModal');
  if (!modal) return;

  const overlay = modal.querySelector('[data-close]');
  const btnClose = modal.querySelector('.cdcc__modal__close');
  const btnPrev  = modal.querySelector('[data-prev]');
  const btnNext  = modal.querySelector('[data-next]');
  const imgEl    = modal.querySelector('#teamModalImg');
  const titleEl  = modal.querySelector('#teamModalTitle');
  const typeEl   = modal.querySelector('#teamModalType');
  const qualEl   = modal.querySelector('#teamModalQualif');
  const descEl   = modal.querySelector('#teamModalDesc');
  const specsEl  = modal.querySelector('#teamModalSpecialisations');
  const hoursEl  = modal.querySelector('#teamModalHours');

  // Helper: is this item allowed in the modal/gallery?
  function isAllowed(li) {
    // Preferred: honor an explicit flag if you add it in PHP
    if (li.dataset.canModal === '0') return false;

    // Fallback: exclude by ACF "type" label text if it contains "assistante" (plural/s, accents not required)
    const typeText = (li.dataset.type || '').toLowerCase();
    if (/\bassistant(e|es)?\b/.test(typeText)) return false;

    // Optional: if you already add a class, you can use: if (!li.classList.contains('is-praticien')) return false;
    return true;
  }

  // Build the filtered gallery list and assign modal indices
  const allItems = Array.from(document.querySelectorAll('.team__item'));
  const items = allItems.filter(isAllowed);
  items.forEach((el, idx) => { el.dataset.modalIndex = String(idx); });

  if (!items.length) return; // nothing to show at all

  let index = -1;
  let lastFocused = null;

  function render(idx) {
    const li = items[idx];
    if (!li) return;

    const name = li.dataset.name || '';
    const type = li.dataset.type || '';
    const qual = li.dataset.qualification || '';
    const img  = li.dataset.image || '';
    const hours = li.dataset.hours || '';
    const html = li.querySelector('.team__item__details')?.innerHTML || '';

    imgEl.src = img || '';
    imgEl.alt = name;
    titleEl.textContent = name;
    typeEl.textContent  = type;
    qualEl.textContent  = qual;
    hoursEl.innerHTML = hours;

    modal.querySelector('.cdcc__modal__meta .sep').style.display =
      (type && qual) ? 'inline' : 'none';

    descEl.innerHTML = html;

    // Specialisations
    specsEl.innerHTML = '';
    let specs = [];
    try { specs = JSON.parse(li.dataset.specs || '[]'); } catch (e) { specs = []; }
    if (Array.isArray(specs) && specs.length) {
      specs.forEach(label => {
        const liEl = document.createElement('li');
        liEl.textContent = label;
        specsEl.appendChild(liEl);
      });
      specsEl.style.display = '';
    } else {
      specsEl.style.display = 'none';
    }

    // Disable nav if single item
    const single = items.length <= 1;
    btnPrev.disabled = single;
    btnNext.disabled = single;
  }

  function open(idx) {
    index = (idx >= 0 ? idx : 0);
    render(index);
    lastFocused = document.activeElement;
    modal.setAttribute('aria-hidden', 'false');
    document.body.classList.add('modal-open');
    btnClose.focus();
    trapSetup();
  }

  function close() {
    modal.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('modal-open');
    trapTeardown();
    if (lastFocused && typeof lastFocused.focus === 'function') lastFocused.focus();
  }

  function next() { index = (index + 1) % items.length; render(index); }
  function prev() { index = (index - 1 + items.length) % items.length; render(index); }

  // Delegated open — only from *allowed* items
  document.addEventListener('click', (e) => {
    const li = e.target.closest('.team__item');
    if (!li) return;

    // Ignore disallowed items
    if (!isAllowed(li)) return;

    e.preventDefault();
    const modalIndex = Number(li.dataset.modalIndex ?? -1);
    if (modalIndex >= 0) open(modalIndex);
  });

  document.addEventListener('keydown', (e) => {
    if (e.key !== 'Enter' && e.key !== ' ') return;
    const li = document.activeElement?.closest?.('.team__item');
    if (!li || !isAllowed(li)) return;
    e.preventDefault();
    const modalIndex = Number(li.dataset.modalIndex ?? -1);
    if (modalIndex >= 0) open(modalIndex);
  });

  // Modal controls
  overlay.addEventListener('click', close);
  btnClose.addEventListener('click', close);
  btnNext.addEventListener('click', next);
  btnPrev.addEventListener('click', prev);

  document.addEventListener('keydown', (e) => {
    if (modal.getAttribute('aria-hidden') === 'true') return;
    if (e.key === 'Escape') close();
    if (e.key === 'ArrowRight' && items.length > 1) next();
    if (e.key === 'ArrowLeft'  && items.length > 1) prev();
  });

  // Simple focus trap
  let trapHandler = null;
  function trapSetup() {
    trapHandler = (e) => {
      if (e.key !== 'Tab') return;
      const focusables = modal.querySelectorAll(
        'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
      );
      const f = Array.from(focusables).filter(el => !el.hasAttribute('disabled') && el.offsetParent !== null);
      if (!f.length) return;
      const first = f[0], last = f[f.length - 1];
      if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
      else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
    };
    document.addEventListener('keydown', trapHandler);
  }
  function trapTeardown() {
    if (trapHandler) document.removeEventListener('keydown', trapHandler);
    trapHandler = null;
  }
})();
