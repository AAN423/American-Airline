<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/discord_api.php';

// --- Validasi dasar ---
if (empty($_GET['code']) || empty($_GET['state']) || !isset($_SESSION['oauth_state'])) {
    header('Location: ../index.php?err=denied');
    exit;
}
if (!hash_equals($_SESSION['oauth_state'], $_GET['state'])) {
    header('Location: ../index.php?err=denied');
    exit;
}
unset($_SESSION['oauth_state']);

// --- Tukar code -> access token ---
$tokenResp = discord_exchange_code($_GET['code']);
if ($tokenResp['status'] !== 200 || empty($tokenResp['json']['access_token'])) {
    header('Location: ../index.php?err=oauth_failed');
    exit;
}
$accessToken = $tokenResp['json']['access_token'];

// --- Ambil profil Discord user ---
$userResp = discord_get_user($accessToken);
if ($userResp['status'] !== 200 || empty($userResp['json']['id'])) {
    header('Location: ../index.php?err=oauth_failed');
    exit;
}
$discordUser = $userResp['json'];
$discordId = $discordUser['id'];
$username = $discordUser['username'] ?? 'Unknown';
if (!empty($discordUser['global_name'])) {
    $username = $discordUser['global_name'];
}
$avatarHash = $discordUser['avatar'] ?? null;

// --- Cek member server + role (pakai bot token) ---
$memberResp = discord_get_guild_member($discordId);
if ($memberResp['status'] === 404) {
    // Bukan member server
    header('Location: ../index.php?err=not_member');
    exit;
}
if ($memberResp['status'] !== 200 || empty($memberResp['json'])) {
    header('Location: ../index.php?err=oauth_failed');
    exit;
}
$memberRoleIds = $memberResp['json']['roles'] ?? [];

// --- Ambil daftar role server untuk cocokkan ID -> nama ---
$rolesResp = discord_get_guild_roles();
$roleNames = [];
$isAdmin = false;
if ($rolesResp['status'] === 200 && is_array($rolesResp['json'])) {
    $adminRolesLower = array_map('strtolower', ADMIN_ROLE_NAMES);
    foreach ($rolesResp['json'] as $role) {
        if (in_array($role['id'], $memberRoleIds, true)) {
            $roleNames[] = $role['name'];
            if (in_array(strtolower($role['name']), $adminRolesLower, true)) {
                $isAdmin = true;
            }
        }
    }
}

// --- Simpan / update user di database ---
$pdo = get_db();
$stmt = $pdo->prepare("SELECT id FROM users WHERE discord_id = ?");
$stmt->execute([$discordId]);
$existing = $stmt->fetch();

$rolesJson = json_encode($roleNames);

if ($existing) {
    $upd = $pdo->prepare("UPDATE users SET username = ?, avatar = ?, roles = ?, is_admin = ?, last_login = NOW() WHERE discord_id = ?");
    $upd->execute([$username, $avatarHash, $rolesJson, $isAdmin ? 1 : 0, $discordId]);
} else {
    $ins = $pdo->prepare("INSERT INTO users (discord_id, username, avatar, roles, is_admin, last_login) VALUES (?, ?, ?, ?, ?, NOW())");
    $ins->execute([$discordId, $username, $avatarHash, $rolesJson, $isAdmin ? 1 : 0]);
}

// --- Set session ---
$_SESSION['discord_id'] = $discordId;
$_SESSION['username'] = $username;
$_SESSION['avatar'] = discord_avatar_url($discordId, $avatarHash);
$_SESSION['is_admin'] = $isAdmin;
$_SESSION['roles'] = $roleNames;

header('Location: ../dashboard.php');
exit;
