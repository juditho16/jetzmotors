<?php
session_start();
require_once "../../../config/db.php";

if (empty($_SESSION['otp_verified']) || empty($_SESSION['reset_user_id'])) {
    header("Location: forgot_password.php");
    exit;
}

$user_id = $_SESSION['reset_user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        $hashed = password_hash($new_password, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("UPDATE users SET password=? WHERE id=?");
        $stmt->execute([$hashed, $user_id]);

        // Clear session
        session_unset();
        session_destroy();

        header("Location: user.php?reset=success");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Reset Password - Jetz Motors</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/app.css">
<style>
body { background: var(--color-base-100); overflow-x:hidden; min-height:100vh; }
.reset-container {
    display:flex;align-items:center;justify-content:center;height:100vh;padding:1rem;
}
.reset-card {
    background:#fff;border-radius:25px;box-shadow:0 15px 35px rgba(0,0,0,0.1);
    padding:2rem;max-width:400px;width:100%;text-align:center;
}
.reset-card h2 {color:var(--color-primary);font-weight:700;margin-bottom:0.5rem;}
.form-control {border-radius:50px;background-color:var(--color-base-200);border:none;}
.btn-primary {border-radius:50px;background-color:var(--color-primary);color:var(--color-primary-content);font-weight:600;}
.btn-primary:hover {background-color:var(--color-secondary);}
</style>
</head>
<body>
<div class="reset-container">
    <div class="reset-card">
        <img src="../assets/components/work.gif" width="100" class="mb-3" alt="Reset">
        <h2>Reset Your Password</h2>
        <p>Enter your new password below</p>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <input type="password" name="new_password" class="form-control" placeholder="New Password" required>
            </div>
            <div class="mb-3">
                <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" required>
            </div>
            <button class="btn btn-primary w-100">Reset Password</button>
        </form>
    </div>
</div>
</body>
</html>
