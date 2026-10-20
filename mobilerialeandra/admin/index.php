<?php
session_start();

// Nëse është tashmë i loguar, shko te dashboard
if (isset($_SESSION['admin_logged_in'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

// Kredencialet e adminit — ndrysho sipas dëshirës
define('ADMIN_USER', 'admin');
define('ADMIN_PASS', 'leandra2026');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === ADMIN_USER && $password === ADMIN_PASS) {
        $_SESSION['admin_logged_in'] = true;
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Emri i përdoruesit ose fjalëkalimi është i gabuar!';
    }
}
?>
<!DOCTYPE html>
<html lang="sq">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin — Mobileria Leandra</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet" />
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Jost', sans-serif;
      background: #1a1007;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .login-box {
      background: rgba(255,255,255,0.04);
      border: 1px solid rgba(255,255,255,0.08);
      border-radius: 20px;
      padding: 52px 44px;
      width: 100%;
      max-width: 420px;
      box-shadow: 0 20px 60px rgba(0,0,0,0.5);
    }
    .login-logo {
      font-family: 'Playfair Display', serif;
      font-size: 1.5rem;
      color: #c9963e;
      text-align: center;
      margin-bottom: 8px;
    }
    .login-sub {
      text-align: center;
      font-size: 0.8rem;
      color: #8a7060;
      letter-spacing: 0.12em;
      text-transform: uppercase;
      margin-bottom: 36px;
    }
    label {
      display: block;
      font-size: 0.78rem;
      font-weight: 600;
      letter-spacing: 0.1em;
      text-transform: uppercase;
      color: #8a7060;
      margin-bottom: 8px;
    }
    input {
      width: 100%;
      padding: 12px 16px;
      background: rgba(255,255,255,0.06);
      border: 1.5px solid rgba(255,255,255,0.1);
      border-radius: 8px;
      color: #f5ede0;
      font-family: 'Jost', sans-serif;
      font-size: 0.95rem;
      outline: none;
      margin-bottom: 20px;
      transition: border-color 0.3s;
    }
    input:focus { border-color: #c9963e; }
    button {
      width: 100%;
      padding: 14px;
      background: linear-gradient(135deg, #c9963e, #8b5e3c);
      color: #fff;
      font-family: 'Jost', sans-serif;
      font-size: 0.95rem;
      font-weight: 600;
      border: none;
      border-radius: 50px;
      cursor: pointer;
      letter-spacing: 0.08em;
      transition: opacity 0.3s;
    }
    button:hover { opacity: 0.88; }
    .error {
      background: rgba(200,60,60,0.12);
      border: 1px solid rgba(200,60,60,0.35);
      border-radius: 8px;
      color: #e08080;
      padding: 12px 16px;
      font-size: 0.88rem;
      margin-bottom: 20px;
      text-align: center;
    }
  </style>
</head>
<body>
  <div class="login-box">
    <div class="login-logo">Mobileria Leandra</div>
    <div class="login-sub">Panel Administrativ</div>

    <?php if ($error): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
      <label>Emri i përdoruesit</label>
      <input type="text" name="username" placeholder="admin" required />

      <label>Fjalëkalimi</label>
      <input type="password" name="password" placeholder="••••••••" required />

      <button type="submit">Hyr në Panel</button>
    </form>
  </div>
</body>
</html>