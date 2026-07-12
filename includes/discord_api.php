<?php
require_once __DIR__ . '/../config.php';

/**
 * Generic cURL request helper.
 */
function discord_curl($url, $method = 'GET', $headers = [], $postFields = null) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    if ($postFields !== null) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
    }
    $response = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    return [
        'status' => $status,
        'body' => $response,
        'error' => $error,
        'json' => $response ? json_decode($response, true) : null,
    ];
}

/**
 * Tukar "code" dari redirect Discord menjadi access token.
 */
function discord_exchange_code($code) {
    $data = [
        'client_id' => DISCORD_CLIENT_ID,
        'client_secret' => DISCORD_CLIENT_SECRET,
        'grant_type' => 'authorization_code',
        'code' => $code,
        'redirect_uri' => DISCORD_REDIRECT_URI,
    ];

    return discord_curl(
        'https://discord.com/api/oauth2/token',
        'POST',
        ['Content-Type: application/x-www-form-urlencoded'],
        http_build_query($data)
    );
}

/**
 * Ambil profil user Discord yang sedang login (pakai access token OAuth).
 */
function discord_get_user($accessToken) {
    return discord_curl(
        'https://discord.com/api/users/@me',
        'GET',
        ['Authorization: Bearer ' . $accessToken]
    );
}

/**
 * Cek apakah user adalah member server (guild) dan ambil role ID miliknya.
 * Pakai BOT TOKEN, bukan access token user.
 */
function discord_get_guild_member($userId) {
    return discord_curl(
        'https://discord.com/api/guilds/' . DISCORD_GUILD_ID . '/members/' . $userId,
        'GET',
        ['Authorization: Bot ' . DISCORD_BOT_TOKEN]
    );
}

/**
 * Ambil semua role di server (untuk mencocokkan role ID -> nama role).
 */
function discord_get_guild_roles() {
    return discord_curl(
        'https://discord.com/api/guilds/' . DISCORD_GUILD_ID . '/roles',
        'GET',
        ['Authorization: Bot ' . DISCORD_BOT_TOKEN]
    );
}

/**
 * URL avatar Discord user. Kalau tidak punya avatar custom, pakai avatar default.
 */
function discord_avatar_url($discordId, $avatarHash) {
    if ($avatarHash) {
        $ext = strpos($avatarHash, 'a_') === 0 ? 'gif' : 'png';
        return "https://cdn.discordapp.com/avatars/{$discordId}/{$avatarHash}.{$ext}?size=128";
    }
    // Default embed avatar (index 0-5)
    $idx = (int)((intval($discordId) >> 22) % 6);
    return "https://cdn.discordapp.com/embed/avatars/{$idx}.png";
}
