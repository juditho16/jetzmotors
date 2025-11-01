<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Jetz Motors Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            font-family: "Segoe UI", sans-serif;
            background: #f8f9fa;
        }

        .nav-link.active {
            background-color: #0d6efd !important;
            color: #fff !important;
        }

        /* Default expanded */
        .sidebar {
            width: 230px !important;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #1e293b;
            /* dark background */
            color: #e2e8f0;
            display: flex;
            flex-direction: column;
            padding: 1rem;
            transition: width 0.3s;
            overflow-x: hidden;
            /* ✅ prevent content spill */
        }

        /* Collapsed */
        #sidebar.collapsed {
            width: 80px !important;
            /* ✅ give enough room for icons */
        }

        /* Hide text when collapsed */
        #sidebar.collapsed .sidebar-text {
            display: none;
        }

        /* Center icons when collapsed */
        #sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 0.75rem 0.5rem;
            /* ✅ reduce side padding */
        }

        /* Reset icon spacing */
        #sidebar.collapsed .nav-link i {
            margin: 0;
        }

        /* Adjust content margin when collapsed */
        #sidebar.collapsed+#content {
            margin-left: 80px !important;
        }

        .sidebar .brand {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 2rem;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            /* clickable brand */
        }

        .sidebar .nav-link {
            color: #cbd5e1;
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: background 0.2s;
        }

        .sidebar .nav-link:hover {
            background-color: #334155;
            color: #fff;
        }

        .sidebar .nav-link.active {
            background-color: #0d6efd;
            color: #fff;
        }

        .content {
            margin-left: 230px !important;
            /* push content to right */
            padding: 2rem;
            transition: margin-left 0.3s;
        }

        .logout-btn {
            margin-top: auto;
        }
    </style>
</head>

<body>