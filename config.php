<?php
/**
 * =====================================================
 *  KONFIGURASI American Airlines PTFS Virtual Airline
 * =====================================================
 *  ISI SEMUA NILAI DI BAWAH INI SESUAI PUNYA KAMU.
 *  Jangan share file ini ke orang lain (ada rahasia bot & OAuth).
 * =====================================================
 */

// ---------- DATABASE (isi sesuai cPanel InfinityFree) ----------
define('DB_HOST', 'sqlXXX.infinityfree.com');   // Contoh: sql123.infinityfree.com
define('DB_NAME', 'epiz_xxxxxxxx_aaptfs');      // Nama database
define('DB_USER', 'epiz_xxxxxxxx');             // Username database
define('DB_PASS', 'ISI_PASSWORD_DB');           // Password database

// ---------- DISCORD OAUTH2 APP ----------
// Ambil di https://discord.com/developers/applications -> App kamu -> OAuth2
define('DISCORD_CLIENT_ID', 'ISI_CLIENT_ID');
define('DISCORD_CLIENT_SECRET', 'ISI_CLIENT_SECRET');

// Harus PERSIS sama dengan yang didaftarkan di Discord Developer Portal (OAuth2 -> Redirects)
// Contoh: https://namadomainkamu.rf.gd/auth/discord_callback.php
define('DISCORD_REDIRECT_URI', 'https://ISI-DOMAIN-KAMU/auth/discord_callback.php');

// ---------- DISCORD BOT (untuk cek member & role) ----------
// Buat bot di aplikasi Discord yang sama -> tab "Bot" -> Reset Token
// Bot WAJIB sudah di-invite ke server Discord kamu, dan aktifkan
// intent "SERVER MEMBERS INTENT" di tab Bot pada Developer Portal.
define('DISCORD_BOT_TOKEN', 'ISI_BOT_TOKEN');

// ID server Discord vEZY / AA PTFS kamu (klik kanan nama server -> Copy Server ID)
define('DISCORD_GUILD_ID', 'ISI_GUILD_ID');

// ---------- ROLE YANG DIANGGAP ADMIN ----------
// Nama role di server Discord (huruf besar/kecil tidak masalah, akan dicocokkan otomatis)
// Wajib PERSIS sama seperti nama role di server.
define('ADMIN_ROLE_NAMES', ['CEO', 'COO', 'CDO', 'CPA', 'OM']);

// ---------- INFO SITUS ----------
define('SITE_NAME', 'American Airlines PTFS');
define('SITE_URL', 'https://ISI-DOMAIN-KAMU'); // tanpa garis miring di akhir

// ---------- SESSION ----------
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ---------- ERROR REPORTING (matikan display_errors saat sudah live) ----------
error_reporting(E_ALL);
ini_set('display_errors', '0'); // ganti ke '1' hanya saat debugging di localhost
