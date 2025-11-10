<?php
session_start();
require_once "../../../config/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = trim($_POST['phone']);

    // Check if phone exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE phone = ? LIMIT 1");
    $stmt->execute([$phone]);
    $user = $stmt->fetch();

    if ($user) {
        // Generate 6-digit OTP
        $otp = rand(100000, 999999);
        $expires_at = date("Y-m-d H:i:s", strtotime("+10 minutes"));

        // Store OTP in reset_tokens table
        $insert = $pdo->prepare("INSERT INTO reset_tokens (user_id, phone, otp_code, expires_at) VALUES (?, ?, ?, ?)");
        $insert->execute([$user['id'], $phone, $otp, $expires_at]);

        // ✅ For now, store in session (for testing/demo)
        $_SESSION['otp_phone'] = $phone;
        $_SESSION['otp_code'] = $otp;

        // Redirect to verification
        header("Location: verify_otp.php");
        exit;
    } else {
        $error = "Phone number not found.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Forgot Password - Jetz Motors</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../../../assets/css/app.css">
<style>
body {
    background: var(--color-base-100);
    overflow-x: hidden;
    min-height: 100vh;
}

/* ✅ Container Layout */
.forgot-container {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100vh;
    padding: 1rem;
}

/* ✅ Forgot Card */
.forgot-card {
    background: #fff;
    border-radius: 25px;
    box-shadow: 0 15px 35px rgba(0,0,0,0.1);
    padding: 2rem;
    max-width: 400px;
    width: 100%;
    text-align: center;
    position: relative;
}

/* ✅ Image */
.forgot-card img {
    width: 110px;
    margin: 0 auto 1rem auto;
    display: block;
}

/* ✅ Text */
.forgot-card h2 {
    color: var(--color-primary);
    font-weight: 700;
    margin-bottom: 0.4rem;
}

.forgot-card p {
    font-size: 0.85rem;
    color: var(--color-secondary);
    margin-bottom: 1.3rem;
}

/* ✅ Input */
.form-control {
    border-radius: 50px;
    background-color: var(--color-base-200);
    border: none;
    padding: 0.75rem 1rem;
}

/* ✅ Buttons */
.btn-primary {
    border-radius: 50px;
    padding: 0.75rem;
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

/* ✅ Outline button for "Back to Login" */
.btn-outline-primary {
    border-radius: 50px;
    padding: 0.75rem;
    font-weight: 600;
    font-size: 0.9rem;
    border: 2px solid var(--color-primary);
    color: var(--color-primary);
    background: transparent;
    transition: all 0.3s ease;
}

.btn-outline-primary:hover {
    background-color: var(--color-primary);
    color: var(--color-primary-content);
}

/* ✅ Responsive */
@media (max-width: 480px) {
    .forgot-card {
        padding: 1.5rem;
        border-radius: 20px;
        max-width: 300px;
    }
    .forgot-card img {
        width: 85px;
    }
}
</style>
</head>
<body>
<div class="forgot-container">
    <div class="forgot-card">
        <!-- 🔹 Work.gif Logo -->
        <img src="../../../assets/components/work.gif" alt="Forgot Password">
        <h2>Forgot Password</h2>
        <p>Enter your registered phone number to receive an OTP code.</p>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <!-- 🔹 Forgot Password Form -->
        <form method="POST" class="mb-3">
            <div class="mb-3">
                <input type="text" name="phone" class="form-control" placeholder="Enter Phone Number" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Send OTP</button>
        </form>

        <!-- 🔹 Back to Login Button -->
        <a href="../../loading.php" class="btn btn-outline-primary w-100">Back to Login</a>
    </div>
</div>
</body>
</html>
