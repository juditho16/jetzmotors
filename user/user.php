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

    if ($user && password_verify($password, $user['password'])) {
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

    <!-- ✅ Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- ✅ Global Styles -->
    <link rel="stylesheet" href="../assets/css/app.css">

    <style>
        /* ===== ANIMATION ===== */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* ===== LOADING SCREEN ===== */
        #loading-screen {
            position: fixed;
            inset: 0;
            background: var(--color-base-100);
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
            padding-top: 30vh;
            z-index: 9999;
            animation: fadeIn 0.4s ease-in-out;
            overflow: hidden;
        }

        #loading-screen .logo-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            z-index: 3;
            margin-bottom: auto;
        }

        #loading-screen .gear-gif {
            width: 160px;
            height: auto;
            margin-bottom: 1rem;
        }

        #loading-screen h2 {
            font-family: var(--font-tertiary);
            font-weight: 800;
            color: var(--color-primary);
            letter-spacing: 1.5px;
            font-size: clamp(1.4rem, 4vw, 2rem);
        }

        .loading-footer {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 180px;
            overflow: hidden;
        }

        .loading-footer svg {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
        }

        .loading-footer .footer-overlay {
            position: absolute;
            bottom: 40px;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
            width: 100%;
            z-index: 2;
        }

        .loading-footer .footer-overlay p {
            color: var(--color-primary-content);
            font-family: var(--font-secondary);
            font-size: clamp(0.75rem, 2vw, 0.9rem);
            line-height: 1.4;
            margin: 0;
        }

        /* ===== LOGIN SECTION ===== */
        #login-section {
            display: none;
            height: 100vh;
            background: var(--color-base-100);
            position: relative;
            overflow: hidden;
        }

        .login-container {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            padding: 1rem;
            z-index: 1;
        }

        .login-card {
            background: #fff;
            border-radius: 25px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            padding: 2rem;
            width: 100%;
            max-width: 380px;
            text-align: center;
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
            margin-bottom: -60px; /* slightly under the wave */
        }

        .login-card img.work-gif {
            width: 110px;
            height: auto;
            display: block;
            margin: 0 auto 1rem auto;
        }

        .login-card h2 {
            font-family: var(--font-primary);
            font-weight: 700;
            color: var(--color-primary);
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

        .form-control:focus {
            outline: 2px solid var(--color-primary);
            box-shadow: none;
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

        /* ===== FOOTER WAVE (LOGIN) ===== */
        .svg-footer-wrapper {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 200px;
            overflow: hidden;
            z-index: 2;
        }

        .svg-footer-wrapper svg {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transform: scaleX(1.05); /* prevent horizontal compression */
        }

        .footer-overlay-login {
            position: absolute;
            bottom: 35px;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
            width: 100%;
            z-index: 3;
        }

        .footer-overlay-login p {
            color: var(--color-primary-content);
            font-family: var(--font-secondary);
            font-size: 0.8rem;
            line-height: 1.3;
            margin: 0;
        }

        /* ===== RESPONSIVE FIXES ===== */
        @media (max-width: 768px) {
            .login-card {
                max-width: 320px;
                padding: 1.5rem;
                margin-bottom: -40px;
            }

            .login-card img.work-gif {
                width: 90px;
            }

            .login-card h2 {
                font-size: 1.1rem;
            }

            .login-card p {
                font-size: 0.8rem;
            }

            .svg-footer-wrapper {
                height: 200px; /* keep same wave height */
            }

            .svg-footer-wrapper svg {
                transform: scale(1.05);
            }
        }

        @media (max-width: 480px) {
            .login-card {
                max-width: 280px;
                padding: 1.2rem;
                margin-bottom: -30px;
            }

            .login-card img.work-gif {
                width: 75px;
            }

            .login-card h2 {
                font-size: 1rem;
            }

            .login-card p {
                font-size: 0.75rem;
            }

            .btn-primary {
                font-size: 0.8rem;
                padding: 0.6rem;
            }

            .svg-footer-wrapper {
                height: 150px; /* maintain curve depth */
            }
        }

        .fade-in { animation: fadeIn 1.2s ease-in-out; }
    </style>
</head>

<body>
    <!-- ✅ LOADING SCREEN -->
    <div id="loading-screen">
        <div class="logo-wrapper">
            <img src="../assets/components/gears.gif" alt="Loading..." class="gear-gif">
            <h2>JETZ MOTORPARTS</h2>
        </div>

        <div class="loading-footer">
            <svg viewBox="0 0 500 180" preserveAspectRatio="none">
            <path d="M-5.92,50 C150.00,200.00 349.77,-70.00 500.00,60.00 L500.00,180.00 L0.00,180.00 Z"
                  style="stroke: none; fill: var(--color-primary);"></path>
        </svg>
            <div class="footer-overlay">
                <p>Vehicle appointment, service repair and membership system.<br>©2025</p>
            </div>
        </div>
    </div>

    <!-- ✅ LOGIN SECTION -->
    <section id="login-section">
        <div class="login-container fade-in">
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

                <p><a href="forgot_password.php" class="text-decoration-none" style="color: var(--color-primary);">Forgot Password?</a></p>
                <p class="mt-2" style="font-size: 0.9rem;">Don’t have an account? 
                    <a href="signup.php" class="fw-semibold text-decoration-none" style="color: var(--color-secondary);">Sign Up</a>
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
    </section>

    <!-- ✅ Transition Script -->
    <script>
        window.addEventListener("load", () => {
            setTimeout(() => {
                const loader = document.getElementById("loading-screen");
                loader.style.transition = "opacity 0.3s ease";
                loader.style.opacity = "0";
                setTimeout(() => {
                    loader.style.display = "none";
                    document.getElementById("login-section").style.display = "block";
                }, 300);
            }, 5000);
        });
    </script>
</body>
</html>
