document.addEventListener('DOMContentLoaded', function () {
    (function initSimpleToast() {
        if (document.getElementById('simple-toast')) return;
        const style = document.createElement('style');
        style.textContent = `
            #simple-toast { position: fixed; right: 16px; bottom: 16px; z-index:9999; display:flex; flex-direction:column; gap:8px; }
            .simple-toast-item {
                background: rgba(0,0,0,0.8);
                color: #fff;
                padding: 8px 12px;
                border-radius: 6px;
                font-family: system-ui, -apple-system, "Segoe UI", Roboto, Arial;
                opacity: 0; transform: translateY(8px);
                transition: opacity .18s ease, transform .18s ease;
                max-width: 320px;
                word-break: break-word;
            }
            .simple-toast-item.show { opacity: 1; transform: translateY(0); }
        `;
        document.head.appendChild(style);
        const container = document.createElement('div');
        container.id = 'simple-toast';
        document.body.appendChild(container);
    })();

    function showToast(msg, timeout = 3000) {
        const container = document.getElementById('simple-toast');
        if (!container) return;
        const item = document.createElement('div');
        item.className = 'simple-toast-item';
        item.textContent = msg;
        container.appendChild(item);

        requestAnimationFrame(() => item.classList.add('show'));
        const t = setTimeout(() => {
            item.classList.remove('show');
            item.addEventListener('transitionend', () => item.remove(), { once: true });
        }, timeout);
        item.addEventListener('click', () => {
            clearTimeout(t);
            item.classList.remove('show');
            item.addEventListener('transitionend', () => item.remove(), { once: true });
        });
    }

    const searchForm = document.querySelector('.search-bar');
    if (searchForm) {
        const input = searchForm.querySelector('input');
        searchForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const q = (input.value || '').trim().toLowerCase();
            const cards = document.getElementsByClassName('product-card');
            for (let i = 0; i < cards.length; i++) {
                const card = cards[i];
                const titleEl = card.querySelector('.product-title');
                const locEl = card.querySelector('.product-location');
                const title = titleEl ? titleEl.textContent.toLowerCase() : '';
                const loc = locEl ? locEl.textContent.toLowerCase() : '';
                card.style.display = (!q || title.includes(q) || loc.includes(q)) ? '' : 'none';
            }
            showToast(q ? `Menampilkan hasil untuk "${q}"` : 'Menampilkan semua produk', 2500);
        });
    }

    const badges = document.getElementsByClassName('badge');
    function changeBadge(i, delta) {
        if (!badges[i]) return;
        const n = parseInt(badges[i].textContent || '0', 10) || 0;
        badges[i].textContent = Math.max(0, n + delta);
    }

    document.addEventListener('click', function (e) {
        const cartBtn = e.target.closest && e.target.closest('.cart');
        const wishBtn = e.target.closest && e.target.closest('.wishlist');

        if (cartBtn) {
            e.preventDefault();
            changeBadge(0, 1);
            const card = cartBtn.closest('.product-card');
            const title = card && card.querySelector('.product-title') ? card.querySelector('.product-title').textContent : 'Produk';
            showToast(`${title} ditambahkan ke keranjang`, 2500);
        }

        if (wishBtn) {
            e.preventDefault();
            const card = wishBtn.closest('.product-card');
            const icon = wishBtn.querySelector('i');
            const wished = icon && icon.dataset && icon.dataset.wished === '1';
            if (wished) {
                if (icon) { icon.style.color = ''; icon.dataset.wished = '0'; }
                changeBadge(1, -1);
                showToast('Dihapus dari wishlist', 2000);
            } else {
                if (icon) { icon.style.color = '#b22222'; icon.dataset.wished = '1'; }
                changeBadge(1, 1);
                const title = card && card.querySelector('.product-title') ? card.querySelector('.product-title').textContent : 'Produk';
                showToast(`${title} ditambahkan ke wishlist`, 2500);
            }
        }
    });

    const contactForm = document.querySelector('.contact-form');
    if (contactForm) {
        contactForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const nama = (contactForm.querySelector('input[name="nama"]') || {}).value || '';
            const email = (contactForm.querySelector('input[name="email"]') || {}).value || '';
            const pesan = (contactForm.querySelector('textarea[name="pesan"]') || {}).value || '';

            if (!nama || !email || !pesan) {
                showToast('Harap lengkapi semua kolom.', 2500);
                return;
            }
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                showToast('Alamat email tidak valid.', 2500);
                return;
            }

            showToast('Terima kasih! Pesan Anda telah dikirim.', 2500);
            contactForm.reset();
        });
    }

    const actionLinks = document.querySelectorAll('.product-actions a');
    actionLinks.forEach(a => a.setAttribute('role', 'button'));

    window.addEventListener('load', function () {
        const cards = document.getElementsByClassName('product-card');
        for (let i = 0; i < cards.length; i++) {
            if (cards[i].style.display === 'none') cards[i].style.display = '';
        }
    });
});