<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/functions.php';

// If already logged in, go straight to the dashboard
if (!empty($_SESSION['discord_id'])) {
    header('Location: dashboard.php');
    exit;
}

$err = $_GET['err'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e(SITE_NAME) ?> | Login</title>
<link rel="icon" href="assets/img/logo.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="login-body">

  <div class="login-wrap">
    <div class="login-panel">
      <img src="assets/img/logo.png" alt="American Airlines PTFS" class="login-logo">
      <p class="login-eyebrow">FLIGHT DECK ACCESS</p>
      <h1 class="login-title">American Airlines <span>PTFS</span></h1>
      <p class="login-sub">Community-driven Virtual Airline &mdash; Pilot Training Flight Simulator</p>

      <?php if ($err === 'not_member'): ?>
        <div class="alert alert-error">Your Discord account is not a member of the American Airlines PTFS server. Join the server first, then log in again.</div>
      <?php elseif ($err === 'denied'): ?>
        <div class="alert alert-error">Login was cancelled.</div>
      <?php elseif ($err === 'oauth_failed'): ?>
        <div class="alert alert-error">Discord login failed. Please try again.</div>
      <?php endif; ?>

      <a href="auth/discord_login.php" class="btn-discord">
        <svg width="22" height="22" viewBox="0 0 127.14 96.36" fill="currentColor" aria-hidden="true"><path d="M107.7,8.07A105.15,105.15,0,0,0,81.47,0a72.06,72.06,0,0,0-3.36,6.83A97.68,97.68,0,0,0,49,6.83,72.37,72.37,0,0,0,45.64,0,105.89,105.89,0,0,0,19.39,8.09C2.79,32.65-1.71,56.6.54,80.21h0A105.73,105.73,0,0,0,32.71,96.36,77.7,77.7,0,0,0,39.6,85.25a68.42,68.42,0,0,1-10.85-5.18c.91-.66,1.8-1.34,2.66-2a75.57,75.57,0,0,0,64.32,0c.87.71,1.76,1.39,2.66,2a68.68,68.68,0,0,1-10.87,5.19,77,77,0,0,0,6.89,11.1A105.25,105.25,0,0,0,126.6,80.22h0C129.24,52.84,122.09,29.11,107.7,8.07ZM42.45,65.69C36.18,65.69,31,60,31,53s5-12.74,11.43-12.74S54,46,53.89,53,48.84,65.69,42.45,65.69Zm42.24,0C78.41,65.69,73.25,60,73.25,53s5-12.74,11.44-12.74S96.23,46,96.12,53,91.08,65.69,84.69,65.69Z"/></svg>
        Login with Discord
      </a>

      <p class="login-note">You must be a member of the American Airlines PTFS Discord server to sign in.</p>
    </div>

    <div class="login-side">
      <div class="side-overlay"></div>
      <div class="side-quote">
        <p>&ldquo;Fly with professionalism. Fly with purpose.&rdquo;</p>
      </div>
    </div>
  </div>

  <footer class="login-footer">
    &copy; <?= date('Y') ?> American Airlines PTFS &middot; Independent Virtual Airline Community &middot; Not affiliated with American Airlines, Inc. or oneworld alliance.
  </footer>

</body>
</html>
