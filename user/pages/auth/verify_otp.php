<?php
session_start();
require_once "../../../config/db.php";

if (!isset($_SESSION['otp_phone'])) {
    header("Location: forgot_password.php");
    exit;
}

$phone = $_SESSION['otp_phone'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $otp = trim($_POST['otp']);

    $stmt = $pdo->prepare("SELECT * FROM reset_tokens WHERE phone = ? AND otp_code = ? AND is_used = 0 ORDER BY id DESC LIMIT 1");
    $stmt->execute([$phone, $otp]);
    $token = $stmt->fetch();

    if ($token && strtotime($token['expires_at']) > time()) {
        $_SESSION['otp_verified'] = true;
        $_SESSION['reset_user_id'] = $token['user_id'];

        // Mark OTP as used
        $pdo->prepare("UPDATE reset_tokens SET is_used = 1 WHERE id = ?")->execute([$token['id']]);

        header("Location: reset_password.php");
        exit;
    } else {
        $error = "Invalid or expired OTP code.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Verify OTP - Jetz Motors</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/app.css">
<style>
body { background: var(--color-base-100); overflow-x:hidden; min-height:100vh; }
.verify-container {
    display:flex;align-items:center;justify-content:center;height:100vh;padding:1rem;
}
.verify-card {
    background:#fff;border-radius:25px;box-shadow:0 15px 35px rgba(0,0,0,0.1);
    padding:2rem;max-width:400px;width:100%;text-align:center;
}
.verify-card h2 {color:var(--color-primary);font-weight:700;margin-bottom:0.5rem;}
.form-control {border-radius:50px;background-color:var(--color-base-200);border:none;text-align:center;}
.btn-primary {border-radius:50px;background-color:var(--color-primary);color:var(--color-primary-content);font-weight:600;}
.btn-primary:hover {background-color:var(--color-secondary);}
</style>
</head>
<body>
<div class="verify-container">
    <div class="verify-card">
        <img src="../assets/components/work.gif" width="100" class="mb-3" alt="OTP">
        <h2>Verify OTP</h2>
        <p>We’ve sent an OTP to your phone<br><strong><?= htmlspecialchars($phone) ?></strong></p>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <input type="text" name="otp" class="form-control" placeholder="Enter 6-digit OTP" required>
            </div>
            <button class="btn btn-primary w-100">Verify</button>
        </form>
    </div>
</div>
</body>
</html>
