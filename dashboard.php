<?php
session_start();

// Klo belum login
if (!isset($_SESSION['username'])) {
    $next = isset($_GET['next']) ? urlencode($_GET['next']) : 'dashboard';
    header('Location: login.php?next=' . $next);
    exit;
}

require_once __DIR__ . '/koneksi.php';

$username = htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8');

$products = [];
$res = $mysqli->query("SELECT id, title, location, price, price_old, discount, image_url FROM products ORDER BY id DESC");
if ($res) {
    while ($row = $res->fetch_assoc()) $products[] = $row;
    $res->free();
}

$v = file_exists(__DIR__ . '/script.js') ? filemtime(__DIR__ . '/script.js') : time();
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Toko Sembako — "Sejahtera"</title>
    <link rel="stylesheet" href="styles.css" />
</head>

<body>
    <header class="top-header">
        <div class="container flex-between">
            <div class="logo">
                <img style="width: 40px;height: 40px;object-fit: contain;"
                    src="https://png.pngtree.com/png-clipart/20230922/original/pngtree-store-logo-icon-grocery-architecture-shopping-vector-png-image_12529212.png"
                    alt="Logo Toko Sembako" />
                <span>Toko Sembako "Sejahtera"</span>
            </div>

            <!-- Search Bar -->
            <form class="search-bar" aria-label="Cari produk">
                <input type="text" placeholder="Cari produk favoritmu disini.." />
                <button type="submit">🔍</button>
            </form>

            <div class="header-actions flex">
                <a href="#" class="icon-btn">🛒<span class="badge">0</span></a>
                <a href="#" class="icon-btn">❤️<span class="badge">0</span></a>

                <div style="display:flex;align-items:center;gap:.5rem;">
                    <span style="color:#fff;background:#3a3a3a;padding:.3rem .6rem;border-radius:4px;">Halo, <?php echo $username; ?></span>
                    <a href="logout.php" class="btn btn-ghost" style="text-decoration:none;color:#333;background:#fff;padding:.4rem .6rem;border-radius:4px;">
                        ⎋ Keluar
                    </a>
                </div>
            </div>
        </div>
    </header>

    <nav class="main-nav" aria-label="Navigasi utama">
        <div class="container">
            <ul class="nav-list flex">
                <li><a href="#home" class="nav-link">🏠 Beranda</a></li>
                <li><a href="#produk" class="nav-link">🛍️ Produk</a></li>
                <li><a href="#kategori" class="nav-link">🗂️ Kategori</a></li>
                <li><a href="#afiliasi" class="nav-link">🤝 Afiliasi</a></li>
                <li><a href="#blog" class="nav-link">📰 Blog</a></li>
                <li><a href="#info" class="nav-link">ℹ️ Info</a></li>
            </ul>
        </div>
    </nav>

    <main>
        <section id="home" class="hero-section">
            <div class="container">
                <h2 class="hero-title">Selamat Datang di Toko Sembako "Sejahtera"</h2>
                <p class="hero-desc">Kami menyediakan beras, minyak, gula, dan kebutuhan pokok lainnya dengan kualitas
                    terbaik serta harga bersaing.</p>
                <a href="#produk" class="btn btn-primary">Lihat Produk</a>
            </div>
        </section>

        <section id="kategori" class="category-section">
            <div class="container">
                <h2 class="section-title">Kategori Produk</h2>
                <div class="grid-4 category-grid">
                    <div class="category-card">
                        <img src="https://www.bing.com/th/id/OIP.DnglTAn-iSx3uhvkjShwQgHaE8?w=276&h=211&c=8&rs=1&qlt=90&o=6&dpr=1.4&pid=3.1&rm=2"
                            alt="Beras" class="category-img" />
                        <span>Beras</span>
                    </div>
                    <div class="category-card">
                        <img src="https://th.bing.com/th/id/OIP.KHj-OY82K-KaLEEA3XTnOAHaEK?w=298&h=180&c=7&r=0&o=7&dpr=1.4&pid=1.7&rm=3"
                            alt="Bumbu Masakan" class="category-img" />
                        <span>Bumbu Masakan</span>
                    </div>
                    <div class="category-card">
                        <img src="https://th.bing.com/th/id/OIP.1d7q1gcoL83fFvqT2Y6wCAHaHa?w=174&h=180&c=7&r=0&o=7&dpr=1.4&pid=1.7&rm=3"
                            alt="Detergent" class="category-img" />
                        <span>Detergent</span>
                    </div>
                    <div class="category-card">
                        <img src="https://th.bing.com/th/id/OIP.mAHiipFUcc1ib9sXohSlsgHaE8?w=232&h=180&c=7&r=0&o=7&dpr=1.4&pid=1.7&rm=3"
                            alt="Gula Pasir" class="category-img" />
                        <span>Gula Pasir</span>
                    </div>
                    <div class="category-card">
                        <img src="https://th.bing.com/th/id/OIP.EFbo57otWokjTUoM9KWg6QHaHa?w=167&h=180&c=7&r=0&o=7&dpr=1.4&pid=1.7&rm=3"
                            alt="Ikan Kaleng" class="category-img" />
                        <span>Ikan Kaleng</span>
                    </div>
                    <div class="category-card">
                        <img src="https://wiratech.co.id/wp-content/uploads/2018/10/Cara-Membuat-Kecap.jpg" alt="Kecap"
                            class="category-img" />
                        <span>Kecap</span>
                    </div>
                    <div class="category-card">
                        <img src="https://th.bing.com/th/id/OIP.SviccsbkJbiMHlVVeFHNDAHaHa?w=170&h=180&c=7&r=0&o=7&dpr=1.4&pid=1.7&rm=3"
                            alt="Kopi Saset" class="category-img" />
                        <span>Kopi Saset</span>
                    </div>
                    <div class="category-card">
                        <img src="https://th.bing.com/th/id/OIP.DeY61yxC2azqR64Sp5JE7QHaEc?w=279&h=180&c=7&r=0&o=5&dpr=1.4&pid=1.7"
                            alt="Mie Instan" class="category-img" />
                        <span>Mie Instan</span>
                    </div>
                    <div class="category-card">
                        <img src="https://th.bing.com/th/id/OIP.SMBt_ixv5QoXRwict-vZ3wAAAA?w=230&h=189&c=7&r=0&o=5&dpr=1.4&pid=1.7"
                            alt="Minuman" class="category-img" />
                        <span>Minuman</span>
                    </div>
                    <div class="category-card">
                        <img src="https://th.bing.com/th/id/OIP.ANetLfS5YhWw2F6X11nx9AHaEB?w=279&h=180&c=7&r=0&o=7&dpr=1.4&pid=1.7&rm=3"
                            alt="Minyak Goreng" class="category-img" />
                        <span>Minyak Goreng</span>
                    </div>
                    <div class="category-card">
                        <img src="https://th.bing.com/th/id/OIP.XL8hOyCYSIaNWrIJG0NChwHaEK?w=290&h=180&c=7&r=0&o=7&dpr=1.4&pid=1.7&rm=3"
                            alt="Pasta Gigi" class="category-img" />
                        <span>Pasta Gigi</span>
                    </div>
                    <div class="category-card">
                        <img src="https://tse1.mm.bing.net/th/id/OIP.MeOMpc_YKn7O_RSZOmoxiwHaFj?pid=ImgDet&w=195&h=146&c=7&dpr=1,4&o=7&rm=3"
                            alt="Pembalut" class="category-img" />
                        <span>Pembalut</span>
                    </div>
                </div>
            </div>
        </section>

        <section id="produk" class="product-section">
            <div class="container">
                <h2 class="section-title">🛍️ PRODUK TERBARU
                    <!-- tombol/tautan kecil ke halaman CRUD -->
                    <a href="crud.php" style="margin-left:12px;font-size:0.9rem;padding:6px 10px;background:#fff;border-radius:6px;border:1px solid #d0d7de;text-decoration:none;color:#333;">
                        Kelola (CRUD)
                    </a>
                </h2>

                <!-- Ganti hardcoded product cards dengan data dari database -->
                <div class="product-grid">
                    <?php if (empty($products)): ?>
                        <p>Tidak ada produk di database. Silakan tambah lewat <a href="crud.php">Kelola (CRUD)</a>.</p>
                    <?php else: foreach ($products as $p): ?>
                        <div class="product-card">
                            <img src="<?php echo $p['image_url'] ? htmlspecialchars($p['image_url'], ENT_QUOTES, 'UTF-8') : 'https://via.placeholder.com/400x220?text=No+Image'; ?>"
                                alt="<?php echo htmlspecialchars($p['title'], ENT_QUOTES, 'UTF-8'); ?>" class="product-img" />
                            <div class="product-info">
                                <span class="product-location">📍 <?php echo htmlspecialchars($p['location'], ENT_QUOTES, 'UTF-8'); ?></span>
                                <h3 class="product-title"><?php echo htmlspecialchars($p['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
                                <div class="product-price-group">
                                    <?php if ($p['price_old'] > 0): ?>
                                        <span class="product-price-old">Rp. <?php echo number_format($p['price_old'], 0, ',', '.'); ?></span>
                                    <?php endif; ?>
                                    <?php if ($p['discount'] > 0): ?>
                                        <span class="product-discount"><?php echo (int)$p['discount']; ?>%</span>
                                    <?php endif; ?>
                                </div>
                                <span class="product-price-new">Rp. <?php echo number_format($p['price'], 0, ',', '.'); ?></span>
                            </div>
                            <div class="product-meta">
                                <span>🎁 0</span>
                                <span>⭐ 0</span>
                            </div>
                            <div class="product-actions">
                                <a href="crud.php?edit=<?php echo (int)$p['id']; ?>" class="btn ghost" style="background:#fff;color:#333;border:1px solid #ddd;padding:.4rem .6rem;border-radius:6px;text-decoration:none;">✏️ Edit</a>

                                <form method="post" action="crud.php" style="background:#fff;color:#333;border:1px solid #ddd;padding:.4rem .6rem;border-radius:6px;text-decoration:none;" onsubmit="return confirm('Hapus produk ini?');">
                                    <input type="hidden" name="action" value="delete" />
                                    <input type="hidden" name="id" value="<?php echo (int)$p['id']; ?>" />
                                    <button type="submit" class="btn-danger">🗑️ Hapus</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; endif; ?>
                </div>
            </div>
        </section>

        <section class="testimonial-section">
            <div class="container">
                <h2 class="section-title">Testimoni Pelanggan</h2>
                <div class="grid-2">
                    <div class="testimonial-card">
                        <p>"Pelayanan cepat, produk berkualitas. Sangat puas belanja di sini!"</p>
                        <span>- Ibu Sari</span>
                    </div>
                    <div class="testimonial-card">
                        <p>"Harga bersaing dan pengiriman tepat waktu. Recomended!"</p>
                        <span>- Pak Adi</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="faq-section">
            <div class="container">
                <h2 class="section-title">FAQ</h2>
                <div class="faq-list">
                    <div class="faq-item">
                        <h4>Apa saja metode pembayaran yang tersedia?</h4>
                        <p>Kami menerima transfer bank, e-wallet, dan COD.</p>
                    </div>
                    <div class="faq-item">
                        <h4>Apakah pengiriman bisa ke seluruh Indonesia?</h4>
                        <p>Ya, kami melayani pengiriman ke seluruh Indonesia.</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="tentang" class="about-section">
            <div class="container grid-2">
                <div>
                    <h2 class="section-title">Tentang Kami</h2>
                    <p>
                        Toko Sembako "Sejahtera" berdedikasi menyediakan kebutuhan pokok untuk keluarga Anda dengan
                        pelayanan ramah dan pengiriman cepat. Kami percaya kualitas dan harga bersaing adalah kunci
                        kepuasan pelanggan.
                    </p>
                    <ul class="value-list">
                        <li>Kualitas terbaik</li>
                        <li>Pengiriman tepat waktu</li>
                        <li>Harga bersaing</li>
                    </ul>
                </div>
                <div>
                    <img src="https://img.freepik.com/free-photo/food-ingredients-wooden-table_23-2148746094.jpg"
                        alt="Tentang Kami - Toko Sembako" class="about-img" />
                </div>
            </div>
        </section>

        <section id="kontak" class="contact-section">
            <div class="container grid-2">
                <div>
                    <h2 class="section-title">Kontak & Lokasi</h2>
                    <p><strong>Alamat:</strong> Jl. Tanjung Santan No. 1, Kota Samarinda.</p>
                    <p><strong>Telp/WA:</strong> 0812-xxxx-xxxx</p>
                    <p><strong>Email:</strong> info@sembakosejahtera.com</p>
                </div>
                <form action="#" method="post" class="contact-form" aria-label="Form kontak">
                    <fieldset>
                        <legend>Kirimi Kami Pesan</legend>
                        <input type="text" name="nama" placeholder="Nama" required />
                        <input type="email" name="email" placeholder="Email" required />
                        <textarea name="pesan" rows="4" placeholder="Pesan Anda" required></textarea>
                        <button type="submit" class="btn btn-primary">Kirim</button>
                    </fieldset>
                </form>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <div class="container flex-between">
            <p>&copy; <span id="tahun">2025</span> Toko Sembako "Sejahtera". Semua hak dilindungi.</p>
            <nav aria-label="Footer navigation">
                <ul class="nav-list flex">
                    <li><a href="#privacy" class="nav-link">Kebijakan Privasi</a></li>
                    <li><a href="#terms" class="nav-link">Syarat & Ketentuan</a></li>
                </ul>
            </nav>
        </div>
        <p class="ref">Referensi desain: <a href="https://mitra-sembako.id" target="_blank"
                rel="noopener noreferrer">mitra-sembako.id</a></p>
    </footer>
    <script src="script.js?v=<?php echo $v; ?>"></script>
</body>

</html>