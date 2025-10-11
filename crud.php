<?php
session_start();

// akses hanya untuk user yang login
if (!isset($_SESSION['username'])) {
    header('Location: login.php?next=crud');
    exit;
}

require_once __DIR__ . '/koneksi.php';

$username = htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8');

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        $title = trim($_POST['title'] ?? '');
        $location = trim($_POST['location'] ?? '');
        $price = floatval($_POST['price'] ?? 0);
        $price_old = floatval($_POST['price_old'] ?? 0);
        $discount = intval($_POST['discount'] ?? 0);
        $image_url = trim($_POST['image_url'] ?? '');

        if ($title === '') $errors[] = 'Judul wajib diisi.';
        if (empty($errors)) {
            $stmt = $mysqli->prepare("INSERT INTO products (title, location, price, price_old, discount, image_url) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('ssddis', $title, $location, $price, $price_old, $discount, $image_url);
            $stmt->execute();
            $stmt->close();
            header('Location: crud.php');
            exit;
        }
    }

    if ($action === 'update') {
        $id = intval($_POST['id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $location = trim($_POST['location'] ?? '');
        $price = floatval($_POST['price'] ?? 0);
        $price_old = floatval($_POST['price_old'] ?? 0);
        $discount = intval($_POST['discount'] ?? 0);
        $image_url = trim($_POST['image_url'] ?? '');

        if ($id <= 0) $errors[] = 'ID tidak valid.';
        if ($title === '') $errors[] = 'Judul wajib diisi.';
        if (empty($errors)) {
            $stmt = $mysqli->prepare("UPDATE products SET title=?, location=?, price=?, price_old=?, discount=?, image_url=? WHERE id=?");
            $stmt->bind_param('ssddisi', $title, $location, $price, $price_old, $discount, $image_url, $id);
            $stmt->execute();
            $stmt->close();
            header('Location: crud.php');
            exit;
        }
    }

    if ($action === 'delete') {
        $id = intval($_POST['id'] ?? 0);
        if ($id > 0) {
            $stmt = $mysqli->prepare("DELETE FROM products WHERE id=?");
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $stmt->close();
        }
        header('Location: crud.php');
        exit;
    }
}

$edit = null;
if (isset($_GET['edit'])) {
    $eid = intval($_GET['edit']);
    if ($eid > 0) {
        $stmt = $mysqli->prepare("SELECT id, title, location, price, price_old, discount, image_url FROM products WHERE id=? LIMIT 1");
        $stmt->bind_param('i', $eid);
        $stmt->execute();
        $res = $stmt->get_result();
        $edit = $res->fetch_assoc();
        $stmt->close();
    }
}

$products = [];
$res = $mysqli->query("SELECT id, title, location, price, price_old, discount, image_url, created_at FROM products ORDER BY id DESC");
if ($res) {
    while ($row = $res->fetch_assoc()) $products[] = $row;
    $res->free();
}

?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>CRUD Produk — Dashboard</title>
    <style>
        .wrap { max-width:1100px; margin:18px auto; padding:12px; }
        .top { display:flex; gap:12px; align-items:center; justify-content:space-between; margin-bottom:12px; }
        .card { background:#fff; padding:12px; border-radius:8px; box-shadow:0 6px 20px rgba(0,0,0,0.05); }
        form .row { display:flex; gap:8px; margin-bottom:8px; flex-wrap:wrap; }
        form label { font-size:0.9rem; display:block; margin-bottom:4px; }
        input[type="text"], input[type="number"] { padding:8px 10px; border:1px solid #d6dde6; border-radius:6px; width:100%; box-sizing:border-box; }
        .grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(240px,1fr)); gap:12px; margin-top:12px; }
        .prod { padding:8px; border:1px solid #eef3f8; border-radius:8px; background:#fafbfd; }
        .prod img { width:100%; height:140px; object-fit:cover; border-radius:6px; background:#eee; }
        .actions { display:flex; gap:8px; margin-top:8px; }
        .btn { padding:8px 10px; border-radius:6px; background:#0077ff; color:#fff; border:none; text-decoration:none; display:inline-block; }
        .btn.ghost { background:#fff; color:#333; border:1px solid #ddd; }
        .btn.danger { background:#fff; color:#333; border:1px solid #ddd; }
        .small { font-size:0.9rem; color:#555; }
        .errors { color:#b22222; margin-bottom:8px; }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="top">
            <h1>Manajemen Produk</h1>
            <div>
                <a href="dashboard.php" class="btn ghost">Kembali ke Dashboard</a>
                <a href="logout.php" class="btn ghost">Logout</a>
            </div>
        </div>

        <div class="card">
            <?php if ($errors): ?>
                <div class="errors"><?php echo implode('<br>', array_map('htmlspecialchars', $errors)); ?></div>
            <?php endif; ?>

            <h3><?php echo $edit ? 'Edit Produk' : 'Tambah Produk Baru'; ?></h3>

            <form method="post" action="crud.php">
                <?php if ($edit): ?>
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="id" value="<?php echo (int)$edit['id']; ?>">
                <?php else: ?>
                    <input type="hidden" name="action" value="create">
                <?php endif; ?>

                <div class="row" style="flex-direction:column;">
                    <label for="title">Judul</label>
                    <input id="title" name="title" type="text" required value="<?php echo $edit ? htmlspecialchars($edit['title'], ENT_QUOTES, 'UTF-8') : ''; ?>">

                    <label for="location">Lokasi</label>
                    <input id="location" name="location" type="text" value="<?php echo $edit ? htmlspecialchars($edit['location'], ENT_QUOTES, 'UTF-8') : ''; ?>">

                    <label for="price">Harga</label>
                    <input id="price" name="price" type="number" step="0.01" value="<?php echo $edit ? htmlspecialchars($edit['price'], ENT_QUOTES, 'UTF-8') : ''; ?>">

                    <label for="price_old">Harga Lama (opsional jika Diskon)</label>
                    <input id="price_old" name="price_old" type="number" step="0.01" value="<?php echo $edit ? htmlspecialchars($edit['price_old'], ENT_QUOTES, 'UTF-8') : ''; ?>">

                    <label for="discount">Diskon (%)</label>
                    <input id="discount" name="discount" type="number" value="<?php echo $edit ? (int)$edit['discount'] : '0'; ?>">

                    <label for="image_url">URL Gambar (opsional)</label>
                    <input id="image_url" name="image_url" type="text" value="<?php echo $edit ? htmlspecialchars($edit['image_url'], ENT_QUOTES, 'UTF-8') : ''; ?>">
                </div>

                <div style="margin-top:10px;">
                    <button type="submit" class="btn"><?php echo $edit ? 'Simpan' : 'Tambah Produk'; ?></button>
                    <?php if ($edit): ?>
                        <a href="crud.php" class="btn ghost">Batal</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <section style="margin-top:14px;">
            <h2 class="small">Daftar Produk</h2>
            <?php if (empty($products)): ?>
                <p class="small">Belum ada produk.</p>
            <?php else: ?>
                <div class="grid">
                    <?php foreach ($products as $p): ?>
                        <div class="prod">
                            <img src="<?php echo $p['image_url'] ? htmlspecialchars($p['image_url'], ENT_QUOTES, 'UTF-8') : 'https://via.placeholder.com/400x220?text=No+Image'; ?>" alt="">
                            <strong><?php echo htmlspecialchars($p['title'], ENT_QUOTES, 'UTF-8'); ?></strong>
                            <div class="small"><?php echo htmlspecialchars($p['location'], ENT_QUOTES, 'UTF-8'); ?></div>
                            <div class="small">Rp. <?php echo number_format($p['price'], 0, ',', '.'); ?> <?php if ($p['discount']>0) echo ' • ' . (int)$p['discount'] . '%'; ?></div>

                            <div class="actions">
                                <a class="btn ghost" href="crud.php?edit=<?php echo (int)$p['id']; ?>">✏️ Edit</a>

                                <form method="post" action="crud.php" style="display:inline;" onsubmit="return confirm('Hapus produk ini?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo (int)$p['id']; ?>">
                                    <button type="submit" class="btn danger">🗑️ Hapus</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </div>
</body>
</html>