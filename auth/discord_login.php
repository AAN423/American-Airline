<?php
require_once __DIR__ . '/../config.php';

// State random untuk mencegah CSRF
$state = bin2hex(random_bytes(16));
$_SESSION['oauth_state'] = $state;

$params = http_build_query([
    'client_id' => DISCORD_CLIENT_ID,
    'redirect_uri' => DISCORD_REDIRECT_URI,
    'response_type' => 'code',
    'scope' => 'identify',
    'state' => $state,
    'prompt' => 'consent',
]);

header('Location: https://discord.com/api/oauth2/authorize?' . $params);
exit;
