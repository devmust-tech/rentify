/**
 * Turbo Navigation - Lightweight PJAX for Rentify
 * Intercepts link clicks & form submissions, swaps content via fetch().
 * Falls back to normal navigation on failure or when JS is disabled.
 */
const MAIN = '#turbo-main', FLASH = '#turbo-flash', HEADER = '#turbo-header';
const TIMEOUT = 10000, PREFETCH_DELAY = 100;

let bar = null, barTimer = null, cache = new Map(), hoverTimer = null, abort = null;

// --- Progress Bar ---
function ensureBar() {
    if (!bar) {
        bar = document.createElement('div');
        bar.id = 'turbo-progress';
        bar.setAttribute('aria-hidden', 'true');
        document.body.appendChild(bar);
    }
    return bar;
}

function showBar() {
    ensureBar();
    bar.style.width = '0%';
    bar.classList.add('turbo-progress-active');
    let w = 0;
    clearInterval(barTimer);
    barTimer = setInterval(() => {
        if (w < 80) { w += (80 - w) * 0.1; bar.style.width = w + '%'; }
    }, 80);
}

function doneBar() {
    clearInterval(barTimer);
    if (!bar) return;
    bar.style.width = '100%';
    setTimeout(() => { bar.classList.remove('turbo-progress-active'); bar.style.width = '0%'; }, 200);
}

function hideBar() {
    clearInterval(barTimer);
    if (bar) { bar.classList.remove('turbo-progress-active'); bar.style.width = '0%'; }
}

// --- Swap ---
function swap(html) {
    const doc = new DOMParser().parseFromString(html, 'text/html');
    const nMain = doc.querySelector(MAIN);
    const cMain = document.querySelector(MAIN);
    if (!nMain || !cMain) return false;

    // Title
    const t = doc.querySelector('title');
    if (t) document.title = t.textContent;

    // CSRF
    const nt = doc.querySelector('meta[name="csrf-token"]');
    const ct = document.querySelector('meta[name="csrf-token"]');
    if (nt && ct) ct.setAttribute('content', nt.getAttribute('content'));

    // Header
    const cH = document.querySelector(HEADER), nH = doc.querySelector(HEADER);
    if (cH && nH) cH.innerHTML = nH.innerHTML;

    // Flash
    const cF = document.querySelector(FLASH), nF = doc.querySelector(FLASH);
    if (cF) cF.innerHTML = nF ? nF.innerHTML : '';

    // Animate main swap
    cMain.classList.add('turbo-swap-out');
    setTimeout(() => {
        cMain.innerHTML = nMain.innerHTML;
        cMain.classList.remove('turbo-swap-out');
        cMain.classList.add('turbo-swap-in');
        alpine(cMain);
        if (cF) alpine(cF);
        if (cH) alpine(cH);
        window.scrollTo({ top: 0, behavior: 'instant' });
        setTimeout(() => cMain.classList.remove('turbo-swap-in'), 300);
    }, 120);
    return true;
}

function alpine(el) {
    if (window.Alpine) try { window.Alpine.initTree(el); } catch (_) {}
}

// --- Helpers ---
function mkAbort() {
    if (abort) abort.abort();
    abort = new AbortController();
    const t = setTimeout(() => abort?.abort(), TIMEOUT);
    abort.signal.addEventListener('abort', () => clearTimeout(t));
    return abort;
}

function intercept(target) {
    if (!(target instanceof Element)) return null;
    const a = target.closest('a');
    if (!a || a.getAttribute('data-turbo') === 'false' || a.hasAttribute('download') || a.target === '_blank') return null;
    const h = a.getAttribute('href');
    if (!h || h.startsWith('#') || h.startsWith('javascript:')) return null;
    try {
        const u = new URL(h, location.origin);
        if (u.origin !== location.origin) return null;
        if (u.pathname === location.pathname && u.hash) return null;
        return u.href;
    } catch { return null; }
}

// --- Navigate ---
async function go(url, push = true) {
    const c = mkAbort();
    showBar();
    try {
        let html = cache.get(url);
        if (!html) {
            const r = await fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-Turbo-Request': 'true', 'Accept': 'text/html' },
                signal: c.signal, credentials: 'same-origin',
            });
            if (!r.ok) throw new Error(r.status);
            html = await r.text();
        }
        if (!swap(html)) throw new Error('no target');
        if (push) history.pushState({ turbo: true }, '', url);
        doneBar();
        cache.delete(url);
    } catch (e) {
        hideBar();
        if (e.name !== 'AbortError') location.href = url;
    } finally { abort = null; }
}

async function submit(form, btn) {
    const action = btn?.formAction || form.action || location.href;
    const method = (btn?.formMethod || form.method || 'GET').toUpperCase();
    try { if (new URL(action, location.origin).origin !== location.origin) { form.submit(); return; } } catch { form.submit(); return; }
    if (form.enctype === 'multipart/form-data' || form.getAttribute('data-turbo') === 'false') { form.submit(); return; }

    const c = mkAbort();
    showBar();
    try {
        const fd = new FormData(form);
        if (btn?.name) fd.append(btn.name, btn.value || '');
        const opts = {
            method,
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-Turbo-Request': 'true', 'Accept': 'text/html' },
            signal: c.signal, credentials: 'same-origin',
        };
        let fetchUrl = action;
        if (method === 'GET') {
            const u = new URL(action, location.origin);
            u.search = new URLSearchParams(fd).toString();
            fetchUrl = u.href;
        } else {
            opts.body = new URLSearchParams(fd);
            opts.headers['Content-Type'] = 'application/x-www-form-urlencoded';
        }
        const r = await fetch(fetchUrl, opts);
        const html = await r.text();
        if (!swap(html)) throw new Error('no target');
        history.pushState({ turbo: true }, '', r.url || fetchUrl);
        doneBar();
    } catch (e) {
        hideBar();
        if (e.name !== 'AbortError') form.submit();
    } finally { abort = null; }
}

// --- Prefetch ---
function prefetch(url) {
    if (cache.has(url)) return;
    fetch(url, { headers: { 'Accept': 'text/html', 'X-Turbo-Request': 'true' }, credentials: 'same-origin' })
        .then(r => r.ok ? r.text() : Promise.reject())
        .then(html => { cache.set(url, html); setTimeout(() => cache.delete(url), 30000); })
        .catch(() => {});
}

// --- Init ---
function init() {
    if (!document.querySelector(MAIN)) return;

    document.addEventListener('click', (e) => {
        if (e.ctrlKey || e.metaKey || e.shiftKey || e.altKey || e.button !== 0) return;
        const url = intercept(e.target);
        if (!url) return;
        e.preventDefault();
        go(url);
    });

    document.addEventListener('submit', (e) => {
        const f = e.target;
        if (!(f instanceof HTMLFormElement) || f.enctype === 'multipart/form-data' || f.getAttribute('data-turbo') === 'false') return;
        try { if (new URL(f.action || location.href, location.origin).origin !== location.origin) return; } catch { return; }
        e.preventDefault();
        submit(f, e.submitter);
    });

    window.addEventListener('popstate', () => go(location.href, false));

    document.addEventListener('mouseover', (e) => {
        const url = intercept(e.target);
        if (!url) return;
        clearTimeout(hoverTimer);
        hoverTimer = setTimeout(() => prefetch(url), PREFETCH_DELAY);
    });
    document.addEventListener('mouseout', () => clearTimeout(hoverTimer));

    history.replaceState({ turbo: true }, '', location.href);
}

export { init };
