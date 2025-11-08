<?php
session_start();
require_once "../config/db.php";
require_once "../lib/functions.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $middle_initial = $_POST['middle_initial'];
    $last_name = $_POST['last_name'];
    $suffix = $_POST['suffix'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // ✅ Auto-generate full name
    $name = trim("$first_name $middle_initial $last_name $suffix");

    $stmt = $pdo->prepare("INSERT INTO users 
        (first_name, middle_initial, last_name, suffix, name, email, phone, address, password) 
        VALUES (?,?,?,?,?,?,?,?,?)");

    $stmt->execute([
        $first_name,
        $middle_initial,
        $last_name,
        $suffix,
        $name,
        $email,
        $phone,
        $address,
        $password
    ]);

    header("Location: user.php?registered=1");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Jetz Motors - Sign Up</title>
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

        /* Dark overlay for background */
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

        .signup-card {
            position: relative;
            z-index: 1;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.4);
            padding: 2rem;
            width: 100%;
            max-width: 500px;
        }

        .form-control,
        textarea {
            border-radius: 8px;
            padding: 0.75rem;
            font-size: 1rem;
        }

        .btn-success {
            padding: 0.75rem;
            font-size: 1.1rem;
            border-radius: 8px;
        }

        @media (max-width: 576px) {
            .signup-card {
                padding: 1.5rem;
                margin: 1rem;
            }
        }
    </style>
</head>

<body>
    <div class="signup-card">
        <h3 class="mb-4 text-center">Create Your Account</h3>
        <form method="POST" class="row g-3">
            <div class="col-md-6">
                <input type="text" name="first_name" class="form-control" placeholder="First Name" required>
            </div>
            <div class="col-md-2">
                <input type="text" name="middle_initial" class="form-control" placeholder="M.I.">
            </div>
            <div class="col-md-4">
                <input type="text" name="last_name" class="form-control" placeholder="Last Name" required>
            </div>
            <div class="col-12">
                <input type="text" name="suffix" class="form-control" placeholder="Suffix (e.g., Jr, Sr, III)">
            </div>
            <div class="col-12">
                <input type="email" name="email" class="form-control" placeholder="Email" required>
            </div>
            <div class="col-12">
                <input type="text" name="phone" class="form-control" placeholder="Phone" required>
            </div>
            <div class="col-12">
                <textarea name="address" class="form-control" placeholder="Address" rows="2" required></textarea>
            </div>
            <div class="col-12">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <div class="col-12">
                <button class="btn btn-success w-100">Sign Up</button>
            </div>
        </form>
        <div class="text-center mt-3">
            <a href="user.php" class="text-decoration-none">Already have an account? Login</a>
        </div>
    </div>
</body>

</html>