<?php
session_start();
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../lib/functions.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Simple plaintext check (adjust later if hashing is used)
    $stmt = $pdo->prepare("SELECT * FROM admin WHERE username=? LIMIT 1");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if ($admin && $password === $admin['password']) {
        $_SESSION['admin_logged'] = true;
        $_SESSION['admin_name'] = $admin['name'];
        header("Location: index.php?page=dashboard");
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Jetz Motors Admin Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/app.css">

  <style>
    body {
      background: var(--color-base-100);
      overflow-x: hidden;
      position: relative;
      min-height: 100vh;
    }

    /* === Center Container === */
    .login-container {
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      padding: 1rem;
      position: relative;
      top: -40px;
    }

    /* === Card === */
    .login-card {
      background: var(--color-base-200);
      border-radius: 25px;
      box-shadow: 0 15px 35px rgba(0,0,0,0.1);
      padding: 2rem;
      width: 100%;
      max-width: 380px;
      text-align: center;
      position: relative;
      z-index: 2;
    }

    .login-card img.work-gif {
      width: 110px;
      margin: 0 auto 1rem auto;
      display: block;
    }

    .login-card h2 {
      color: var(--color-primary);
      font-weight: 700;
      margin-bottom: 0.3rem;
      font-size: 1.3rem;
    }

    .login-card p {
      font-size: 0.85rem;
      color: var(--color-secondary);
      margin-bottom: 1.2rem;
    }

    .form-control {
      border-radius: 50px;
      padding: 0.7rem 1rem;
      background-color: var(--color-base-300);
      border: none;
      font-size: 0.9rem;
      color: var(--color-base-content);
    }

    .form-control:focus {
      box-shadow: 0 0 0 3px oklch(77% 0.152 181.912 / 0.4);
      outline: none;
    }

    .btn-primary {
      border-radius: 50px;
      padding: 0.7rem;
      background-color: var(--color-primary);
      color: var(--color-primary-content);
      font-weight: 600;
      border: none;
      font-size: 0.9rem;
      width: 100%;
      transition: background-color 0.2s ease;
    }

    .btn-primary:hover {
      background-color: var(--color-secondary);
    }

    /* === Wave Footer === */
    .svg-footer-wrapper {
      position: absolute;
      bottom: 0;
      left: 0;
      width: 100%;
      height: 200px;
      overflow: hidden;
      z-index: 1;
    }

    .svg-footer-wrapper svg {
      width: 100%;
      height: 100%;
    }

    .footer-overlay-login {
      position: absolute;
      bottom: 35px;
      left: 50%;
      transform: translateX(-50%);
      text-align: center;
      width: 100%;
    }

    .footer-overlay-login p {
      color: var(--color-primary-content);
      font-family: var(--font-secondary);
      font-size: 0.8rem;
      margin: 0;
    }

    /* === Responsive Design === */
    @media (max-width: 768px) {
      .login-container { top: -60px; }
      .login-card { max-width: 320px; padding: 1.5rem; border-radius: 20px; }
      .login-card img.work-gif { width: 85px; margin-bottom: 0.8rem; }
      .login-card h2 { font-size: 1.1rem; }
      .login-card p { font-size: 0.75rem; margin-bottom: 1rem; }
      .form-control { padding: 0.6rem 0.9rem; font-size: 0.8rem; }
      .btn-primary { font-size: 0.8rem; padding: 0.6rem; }
      .footer-overlay-login p { font-size: 0.75rem; }
      .svg-footer-wrapper { height: 160px; }
    }

    @media (max-width: 480px) {
      .login-container { top: -50px; }
      .login-card { max-width: 270px; padding: 1.2rem; }
      .login-card img.work-gif { width: 75px; }
      .login-card h2 { font-size: 1rem; }
      .login-card p { font-size: 0.7rem; }
      .form-control { padding: 0.55rem 0.8rem; font-size: 0.75rem; }
      .btn-primary { font-size: 0.75rem; padding: 0.55rem; }
      .svg-footer-wrapper { height: 150px; }
    }
  </style>
</head>

<body>
  <div class="login-container">
    <div class="login-card">
      <img src="../assets/components/work.gif" alt="Admin Login" class="work-gif">
      <h2>Admin Access</h2>
      <p>Secure login for Jetz Motors administrative panel</p>

      <?php if (!empty($error)): ?>
        <div class="alert alert-danger py-2"><?= $error ?></div>
      <?php endif; ?>

      <form method="POST">
        <div class="mb-3">
          <input type="text" name="username" class="form-control" placeholder="Username" required>
        </div>
        <div class="mb-4">
          <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>
        <button class="btn btn-primary mb-3">Sign In</button>
      </form>
    </div>
  </div>

  <!-- SVG Footer Wave -->
  <div class="svg-footer-wrapper">
    <svg viewBox="0 0 500 180" preserveAspectRatio="none">
      <path d="M-5.92,50 C150.00,200.00 349.77,-70.00 500.00,60.00 L500.00,180.00 L0.00,180.00 Z"
            style="stroke: none; fill: var(--color-primary);"></path>
    </svg>
    <div class="footer-overlay-login">
      <p>Administrative Access Panel • Jetz Motors 2025</p>
    </div>
  </div>
</body>
</html>
