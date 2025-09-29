document.addEventListener('DOMContentLoaded', function () {
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
            alert(q ? `Menampilkan hasil untuk "${q}"` : 'Menampilkan semua produk');
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
            alert(`${title} ditambahkan ke keranjang`);
        }

        if (wishBtn) {
            e.preventDefault();
            const card = wishBtn.closest('.product-card');
            const icon = wishBtn.querySelector('i');
            const wished = icon && icon.dataset && icon.dataset.wished === '1';
            if (wished) {
                if (icon) { icon.style.color = ''; icon.dataset.wished = '0'; }
                changeBadge(1, -1);
                alert('Dihapus dari wishlist');
            } else {
                if (icon) { icon.style.color = '#b22222'; icon.dataset.wished = '1'; }
                changeBadge(1, 1);
                const title = card && card.querySelector('.product-title') ? card.querySelector('.product-title').textContent : 'Produk';
                alert(`${title} ditambahkan ke wishlist`);
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
                alert('Harap lengkapi semua kolom.');
                return;
            }
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                alert('Alamat email tidak valid.');
                return;
            }

            alert('Terima kasih! Pesan Anda telah dikirim.');
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