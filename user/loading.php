<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Loading - Jetz Motors</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/app.css">

    <style>
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

        body {
            margin: 0;
            background: var(--color-base-100);
            overflow: hidden;
        }

        #loading-screen {
            position: fixed;
            inset: 0;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
            padding-top: 30vh;
            animation: fadeIn 0.4s ease-in-out;
            z-index: 9999;
        }

        #loading-screen .gear-gif {
            width: 160px;
            margin-bottom: 1rem;
        }

        #loading-screen h2 {
            font-family: var(--font-tertiary);
            font-weight: 800;
            color: var(--color-primary);
            font-size: clamp(1.4rem, 4vw, 2rem);
        }

        .loading-footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 180px;
        }

        .loading-footer svg {
            width: 100%;
            height: 100%;
        }

        .footer-overlay {
            position: absolute;
            bottom: 40px;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
            width: 100%;
        }

        .footer-overlay p {
            color: var(--color-primary-content);
            font-family: var(--font-secondary);
            font-size: clamp(0.75rem, 2vw, 0.9rem);
            margin: 0;
        }
    </style>
</head>

<body>
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

    <script>
        // Redirect after 5 seconds
        setTimeout(() => {
            window.location.href = "login.php";
        }, 5000);
    </script>
</body>
</html>
