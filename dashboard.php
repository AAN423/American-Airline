<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/db.php';

require_login();

$tab = $_GET['tab'] ?? 'home';
if (!in_array($tab, ['home', 'recruitment', 'admin'], true)) {
    $tab = 'home';
}

$flash = '';
$flashType = 'success';

// ---------- Handle aksi admin (buka / tutup recruitment) ----------
if ($tab === 'admin' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_admin();
    $pdo = get_db();
    $action = $_POST['action'] ?? '';

    if ($action === 'open_recruitment') {
        $position = trim($_POST['position'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $applyLink = trim($_POST['apply_link'] ?? '');
        $openUntil = trim($_POST['open_until'] ?? '');

        if ($position === '' || $applyLink === '' || $openUntil === '') {
            $flash = 'Jabatan, link apply, dan tanggal tutup wajib diisi.';
            $flashType = 'error';
        } else {
            $stmt = $pdo->prepare("INSERT INTO recruitment (position, description, apply_link, open_until, is_open, created_by) VALUES (?, ?, ?, ?, 1, ?)");
            $stmt->execute([$position, $description, $applyLink, $openUntil, $_SESSION['username']]);
            $flash = 'Recruitment baru berhasil dibuka.';
            $flashType = 'success';
        }
    } elseif ($action === 'close_recruitment') {
        $id = (int)($_POST['id'] ?? 0);
        $stmt = $pdo->prepare("UPDATE recruitment SET is_open = 0 WHERE id = ?");
        $stmt->execute([$id]);
        $flash = 'Recruitment ditutup.';
        $flashType = 'success';
    }
}

if ($tab === 'admin') {
    require_admin();
}

require_once __DIR__ . '/includes/header.php';
?>

<?php if ($flash): ?>
  <div class="alert alert-<?= $flashType === 'error' ? 'error' : 'ok' ?>"><?= e($flash) ?></div>
<?php endif; ?>

<?php if (isset($_GET['err']) && $_GET['err'] === 'forbidden'): ?>
  <div class="alert alert-error">Kamu tidak punya akses ke halaman itu.</div>
<?php endif; ?>

<?php if ($tab === 'home'): ?>
  <?php include __DIR__ . '/includes/tab_home.php'; ?>
<?php elseif ($tab === 'recruitment'): ?>
  <?php include __DIR__ . '/includes/tab_recruitment.php'; ?>
<?php elseif ($tab === 'admin'): ?>
  <?php include __DIR__ . '/includes/tab_admin.php'; ?>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
