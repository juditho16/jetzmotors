<?php
session_start();
require_once "../config/db.php";
require_once "../lib/functions.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email=? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // 🔹 Accept plain passwords (no hashing)
    if ($user && $password === $user['password']) {
        $_SESSION['user_logged'] = true;
        $_SESSION['user_id'] = $user['id'];
        header("Location: index.php?page=profile");
        exit;
    } else {
        $error = "Invalid email or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Jetz Motors - User Login</title>
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

        /* ✅ Container */
        .login-container {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            padding: 1rem;
            position: relative;
            top: -40px; /* moves card slightly above center on desktop */
        }

        /* ✅ Card */
        .login-card {
            background: #fff;
            border-radius: 25px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            padding: 2rem;
            width: 100%;
            max-width: 380px;
            text-align: center;
            position: relative;
            z-index: 2;
        }

        /* ✅ Centered GIF */
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
            background-color: var(--color-base-200);
            border: none;
            font-size: 0.9rem;
        }

        .btn-primary {
            border-radius: 50px;
            padding: 0.7rem;
            background-color: var(--color-primary);
            color: var(--color-primary-content);
            font-weight: 600;
            border: none;
            font-size: 0.9rem;
        }

        .btn-primary:hover {
            background-color: var(--color-secondary);
        }

        /* ✅ Footer Wave */
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

        /* ✅ Mobile View Adjustments */
        @media (max-width: 768px) {
            .login-container {
                top: -60px; /* move higher on mobile */
            }

            .login-card {
                max-width: 300px;
                padding: 1.5rem;
                border-radius: 20px;
            }

            .login-card img.work-gif {
                width: 85px;
                margin-bottom: 0.8rem;
            }

            .login-card h2 {
                font-size: 1.1rem;
            }

            .login-card p {
                font-size: 0.75rem;
                margin-bottom: 1rem;
            }

            .form-control {
                padding: 0.6rem 0.9rem;
                font-size: 0.8rem;
            }

            .btn-primary {
                font-size: 0.8rem;
                padding: 0.6rem;
            }

            .footer-overlay-login p {
                font-size: 0.75rem;
            }

            .svg-footer-wrapper {
                height: 160px;
            }
        }

        @media (max-width: 480px) {
            .login-container {
                top: -50px;
            }

            .login-card {
                max-width: 260px;
                padding: 1.2rem;
            }

            .login-card img.work-gif {
                width: 75px;
            }

            .login-card h2 {
                font-size: 1rem;
            }

            .login-card p {
                font-size: 0.7rem;
            }

            .form-control {
                padding: 0.55rem 0.8rem;
                font-size: 0.75rem;
            }

            .btn-primary {
                font-size: 0.75rem;
                padding: 0.55rem;
            }

            .svg-footer-wrapper {
                height: 150px;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-card">
            <img src="../assets/components/work.gif" alt="Work" class="work-gif">
            <h2>Welcome Back</h2>
            <p>Access your account securely by logging in</p>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Email Address" required>
                </div>
                <div class="mb-4">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
                <button class="btn btn-primary w-100 mb-3">Sign In</button>
            </form>

            <p>
  <a href="pages/auth/forgot_password.php"
     class="text-decoration-none"
     style="color: var(--color-primary);">
     Forgot Password?
  </a>
</p>

<p class="mt-2" style="font-size: 0.9rem;">
  Don’t have an account?
  <a href="signup.php"
     class="fw-semibold text-decoration-none"
     style="color: var(--color-secondary);">
     Sign Up
  </a>
</p>

        </div>
    </div>

    <div class="svg-footer-wrapper">
        <svg viewBox="0 0 500 180" preserveAspectRatio="none">
            <path d="M-5.92,50 C150.00,200.00 349.77,-70.00 500.00,60.00 L500.00,180.00 L0.00,180.00 Z"
                  style="stroke: none; fill: var(--color-primary);"></path>
        </svg>
        <div class="footer-overlay-login">
            <p>Vehicle appointment, service repair and membership system.<br>©2025</p>
        </div>
    </div>
</body>
</html>
