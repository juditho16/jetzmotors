<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Jetz Motors User</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- ✅ responsive -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: "Segoe UI", sans-serif;
            background: #f8f9fa;
            margin: 0;
            padding-bottom: 60px;
            /* space for bottom nav */
        }

        header {
            background: #1e293b;
            color: white;
            text-align: center;
            padding: 1rem;
            font-weight: bold;
            font-size: 1.2rem;
        }

        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background: #1e293b;
            display: flex;
            justify-content: space-around;
            padding: 0.5rem 0;
            border-top: 1px solid #334155;
        }

        .bottom-nav a {
            color: #cbd5e1;
            text-decoration: none;
            text-align: center;
            flex: 1;
            font-size: 0.9rem;
        }

        .bottom-nav a.active {
            color: #0d6efd;
        }

        .bottom-nav i {
            display: block;
            font-size: 1.3rem;
        }
    </style>
</head>

<body>
    <div class="d-flex justify-content-between align-items-center p-3 bg-dark text-white">
        <!-- Brand -->
        <h5 class="m-0">🏍️ Jetz Motors</h5>

        <!-- Mobile Bell -->
        <button class="btn btn-dark position-relative d-md-none" data-bs-toggle="modal"
            data-bs-target="#notificationModal">
            <i class="bi bi-bell fs-5"></i>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                3
            </span>
        </button>

        <!-- Desktop Navigation -->
        <div class="d-none d-md-flex align-items-center gap-3">
            <a href="index.php?page=appointments" class="text-white text-decoration-none">
                <i class="bi bi-calendar-event me-1"></i> Book
            </a>
            <a href="index.php?page=purchases" class="text-white text-decoration-none">
                <i class="bi bi-receipt me-1"></i> Purchases
            </a>
            <a href="index.php?page=history" class="text-white text-decoration-none">
                <i class="bi bi-clock-history me-1"></i> History
            </a>
            <a href="index.php?page=profile" class="text-white text-decoration-none">
                <i class="bi bi-person me-1"></i> Profile
            </a>
            <!-- Desktop Notification Bell -->
            <button class="btn btn-dark position-relative p-0" data-bs-toggle="modal"
                data-bs-target="#notificationModal">
                <i class="bi bi-bell fs-5"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    3
                </span>
            </button>
        </div>
    </div>