<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>JETZ Motorparts</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      margin: 0;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background: linear-gradient(135deg, #0a0a0a, #1a1a1a);
      color: white;
      font-family: "Segoe UI", sans-serif;
      overflow: hidden;
    }

    .splash-content {
      text-align: center;
      animation: fadeIn 1s ease-in-out;
    }

    .splash-logo {
      width: 120px;
      height: auto;
      animation: bounce 1.5s infinite;
    }

    h2 {
      margin-top: 1rem;
      font-weight: 700;
      letter-spacing: 1px;
    }

    @keyframes bounce {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-10px); }
    }

    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }
  </style>
</head>
<body>
  <div class="splash-content">
    <img src="../pictures/logo.png" alt="Jetz Motors Logo" class="splash-logo">
    <h2>JETZ MOTORPARTS</h2>
  </div>

  <script>
    // Wait 3 seconds then redirect to login page
    setTimeout(() => {
      window.location.href = "user.php";
    }, 3000);
  </script>
</body>
</html>
