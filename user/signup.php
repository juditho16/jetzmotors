<?php
session_start();
require_once "functions/signup.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = registerUser($pdo, $_POST);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Jetz Motors - Sign Up</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/app.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background: var(--color-base-100);
            overflow-x: hidden;
            position: relative;
            min-height: 100vh;
        }

        .signup-container {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            padding: 1rem;
            position: relative;
            top: -40px;
        }

        .signup-card {
            background: #fff;
            border-radius: 25px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            width: 100%;
            max-width: 520px;
            text-align: center;
            position: relative;
            z-index: 2;
        }

        .signup-card img {
            width: 110px;
            margin: 0 auto 1rem auto;
            display: block;
        }

        .signup-card h2 {
            color: var(--color-primary);
            font-weight: 700;
            margin-bottom: 0.3rem;
            font-size: 1.3rem;
        }

        .signup-card p {
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
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: var(--color-secondary);
        }

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

        .footer-overlay-signup {
            position: absolute;
            bottom: 35px;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
            width: 100%;
        }

        .footer-overlay-signup p {
            color: var(--color-primary-content);
            font-family: var(--font-secondary);
            font-size: 0.8rem;
            margin: 0;
        }

        @media (max-width: 480px) {
            .signup-card {
                max-width: 270px;
                padding: 1.2rem;
            }

            .signup-card img {
                width: 75px;
            }
        }
    </style>
</head>

<body>
    <div class="signup-container">
        <div class="signup-card">
            <img src="../assets/components/work.gif" alt="Sign Up">
            <h2>Create Your Account</h2>
            <p>Fill in your details below to register</p>

            <form method="POST" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="first_name" class="form-control" placeholder="First Name" required>
                </div>
                <div class="col-md-2">
                    <input type="text" name="middle_initial" class="form-control" placeholder="M.I.">
                </div>
                <div class="col-md-4">
                    <input type="text" name="last_name" class="form-control" placeholder="Last Name" required>
                </div>
                <div class="col-md-2">
                    <input type="text" name="suffix" class="form-control" placeholder="Suffix">
                </div>

                <div class="col-12">
                    <input type="email" name="email" class="form-control" placeholder="Email Address" required>
                </div>
                <div class="col-12">
                    <input type="text" name="phone" class="form-control" placeholder="Phone Number" required>
                </div>
                <div class="col-12">
                    <input type="text" name="address" class="form-control" placeholder="Complete Address" required>
                </div>
                <div class="col-12">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
                <div class="col-12">
                    <button class="btn btn-primary w-100">Sign Up</button>
                </div>
            </form>

            <div class="text-center mt-3">
                <p style="font-size: 0.9rem;">Already have an account? 
                    <a href="loading.php" class="fw-semibold text-decoration-none" style="color: var(--color-secondary);">
                        Log In
                    </a>
                </p>
            </div>
        </div>
    </div>

    <div class="svg-footer-wrapper">
        <svg viewBox="0 0 500 180" preserveAspectRatio="none">
            <path d="M-5.92,50 C150.00,200.00 349.77,-70.00 500.00,60.00 L500.00,180.00 L0.00,180.00 Z"
                style="stroke: none; fill: var(--color-primary);"></path>
        </svg>
        <div class="footer-overlay-signup">
            <p>Vehicle appointment, service repair and membership system.<br>©2025</p>
        </div>
    </div>

    <!-- ✅ SweetAlert2 Toast Notifications -->
    <?php if (isset($result)): ?>
        <script>
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: '<?= $result['status'] === "success" ? "success" : "error" ?>',
            title: "<?= $result['message'] ?>",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });

        <?php if ($result['status'] === 'success'): ?>
            setTimeout(() => {
                window.location.href = "login.php";
            }, 2000);
        <?php endif; ?>
        </script>
    <?php endif; ?>
</body>
</html>
