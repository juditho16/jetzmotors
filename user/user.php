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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: "Segoe UI", sans-serif;
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: url('pictures/image1.jpg') no-repeat center center fixed;
            background-size: cover;
            position: relative;
        }

        /* Dark overlay */
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 0;
        }

        .login-wrapper {
            position: relative;
            z-index: 1;
            max-width: 900px;
            width: 100%;
        }

        .main-card {
            border-radius: 12px;
            overflow: hidden;
            border: 2px solid #fff;
            /* ✅ white outline */
            box-shadow: 0 8px 25px rgba(255, 255, 255, 0.4);
            /* ✅ white drop shadow */
        }

        .card-body {
            padding: 2rem;
        }

        .form-control {
            border-radius: 8px;
            padding: 0.75rem;
            font-size: 1rem;
        }

        .btn-primary {
            padding: 0.75rem;
            font-size: 1.1rem;
            border-radius: 8px;
        }

        .btn-outline-dark {
            padding: 0.75rem;
            border-radius: 8px;
        }

        .image-side {
            background: url('pictures/image11.jpg') no-repeat center center;
            background-size: cover;
            height: 100%;
        }

        @media (max-width: 767px) {
            .image-side {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="login-wrapper container">
        <div class="main-card row g-0">



            <!-- Right: Image Side -->
            <div class="col-md-6 d-none d-md-block">
                <div class="image-side h-100"></div>
            </div>
            <!-- Left: Login Form -->
            <div class="col-md-6 bg-white">
                <div class="card-body">
                    <h2 class="mb-4 text-center">JETZ MOTORPARTS</h2>
                    <h3 class="mb-4 text-center">User Login</h3>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>

                    <form method="POST" class="mb-3">
                        <div class="mb-3">
                            <input type="email" name="email" class="form-control" placeholder="Email" required>
                        </div>
                        <div class="mb-3">
                            <input type="password" name="password" class="form-control" placeholder="Password" required>
                        </div>
                        <button class="btn btn-primary w-100 mb-3">Login</button>
                    </form>

                    <div class="text-center mb-3">
                        <a href="forgot_password.php" class="text-decoration-none">Forgot Password?</a>
                    </div>

                    <div>
                        <a href="signup.php" class="btn btn-outline-dark w-100">Sign Up</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>