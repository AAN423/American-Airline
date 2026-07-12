<?php
$user = current_user();
$activeTab = $_GET['tab'] ?? 'home';
if (!in_array($activeTab, ['home', 'recruitment', 'admin'], true)) {
    $activeTab = 'home';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e(SITE_NAME) ?> | Dashboard</title>
<link rel="icon" href="assets/img/logo.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<header class="topbar">
  <div class="topbar-brand">
    <img src="assets/img/logo.png" alt="logo">
    <span><?= e(SITE_NAME) ?></span>
  </div>

  <nav class="topnav">
    <a href="dashboard.php?tab=home" class="<?= $activeTab === 'home' ? 'active' : '' ?>">Home</a>
    <a href="dashboard.php?tab=recruitment" class="<?= $activeTab === 'recruitment' ? 'active' : '' ?>">Recruitment</a>
    <?php if ($user['is_admin']): ?>
      <a href="dashboard.php?tab=admin" class="<?= $activeTab === 'admin' ? 'active' : '' ?>">Admin</a>
    <?php endif; ?>
  </nav>

  <div class="topbar-user">
    <img src="<?= e($user['avatar']) ?>" alt="avatar" class="user-avatar">
    <span class="user-name"><?= e($user['username']) ?></span>
    <?php if ($user['is_admin']): ?><span class="badge-admin">ADMIN</span><?php endif; ?>
    <a href="logout.php" class="btn-logout">Logout</a>
  </div>
</header>

<main class="page-content">
