<?php
session_start();
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../lib/functions.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Admin login check (plain text for now)
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
    <title>Admin Login - Jetz Motors</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>

<body class="bg-dark text-light d-flex justify-content-center align-items-center" style="height:100vh;">
    <div class="card p-4 bg-secondary" style="width:350px;">
        <h3 class="mb-3 text-center">Admin Login</h3>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button class="btn btn-primary w-100">Login</button>
        </form>
    </div>
</body>

</html>