<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/db.php';

/**
 * Panggil di awal halaman yang wajib login.
 * Kalau belum login, lempar ke halaman login.
 */
function require_login() {
    if (empty($_SESSION['discord_id'])) {
        header('Location: ' . SITE_URL . '/index.php');
        exit;
    }
}

/**
 * Panggil di awal halaman yang khusus admin.
 */
function require_admin() {
    require_login();
    if (empty($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
        header('Location: ' . SITE_URL . '/dashboard.php?tab=home&err=forbidden');
        exit;
    }
}

function current_user() {
    if (empty($_SESSION['discord_id'])) return null;
    return [
        'discord_id' => $_SESSION['discord_id'],
        'username' => $_SESSION['username'] ?? '',
        'avatar' => $_SESSION['avatar'] ?? '',
        'is_admin' => $_SESSION['is_admin'] ?? false,
    ];
}

function e($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Ambil recruitment yang sedang dibuka (belum lewat tanggal open_until).
 */
function get_active_recruitments() {
    $pdo = get_db();
    $stmt = $pdo->prepare("SELECT * FROM recruitment WHERE is_open = 1 AND open_until >= CURDATE() ORDER BY created_at DESC");
    $stmt->execute();
    return $stmt->fetchAll();
}

function get_all_recruitments() {
    $pdo = get_db();
    $stmt = $pdo->prepare("SELECT * FROM recruitment ORDER BY created_at DESC");
    $stmt->execute();
    return $stmt->fetchAll();
}
