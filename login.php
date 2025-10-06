<?php
session_start();

if (isset($_SESSION['username'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if ($username !== '' && $password !== '') {
        session_regenerate_id(true);

        $_SESSION['username'] = $username;

        $next = isset($_POST['next']) && $_POST['next'] !== '' ? $_POST['next'] : 'dashboard.php';
        if (strpos($next, '.php') === false) {
            $next = $next . '.php';
        }

        header('Location: ' . $next);
        exit;
    } else {
        header('Location: login.php?error=1');
        exit;
    }
}

$msg = '';
if (isset($_GET['msg']) && $_GET['msg'] === 'logged_out') {
    $msg = 'Anda telah keluar.';
}
if (isset($_GET['error']) && $_GET['error'] === '1') {
    $error = 'Username dan password wajib diisi.';
}

$next = isset($_GET['next']) ? htmlspecialchars($_GET['next'], ENT_QUOTES, 'UTF-8') : 'dashboard';

$css_v = file_exists(__DIR__ . '/styles.css') ? filemtime(__DIR__ . '/styles.css') : time();
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Masuk — Toko Sembako "Sejahtera"</title>
    <link rel="stylesheet" href="styles.css?v=<?php echo $css_v; ?>" />
</head>

<body class="login-page">
    <main class="login-container">
        <h1>Menu Login Toko Sembako "Sejahtera"</h1>

        <?php if ($msg): ?>
            <p style="color:green;"><?php echo htmlspecialchars($msg, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>

        <?php if ($error): ?>
            <p style="color:red;"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>

        <form action="login.php" method="post" aria-label="Form login">
            <input type="hidden" name="next" value="<?php echo $next; ?>" />
            <div>
                <label for="username">Username</label>
                <input id="username" name="username" type="text" required />
            </div>
            <div>
                <label for="password">Password</label>
                <input id="password" name="password" type="password" required />
            </div>
            <div style="margin-top:.5rem;">
                <button type="submit" class="btn btn-primary">Masuk</button>
            </div>
        </form>

        <p class="login-note" style="margin-top:1rem;">Silahkan Login Saja untuk Masuk</p>
    </main>
</body>

</html>