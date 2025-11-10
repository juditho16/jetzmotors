<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Loading - Jetz Motors</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/app.css">

  <style>
    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    body {
      margin: 0;
      background: var(--color-base-100);
      overflow: hidden;
      height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      position: relative;
    }

    /* ✅ Loading Wrapper */
    #loading-screen {
      position: relative;
      height: 100vh;
      width: 100%;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      text-align: center;
      z-index: 2;
    }

    /* ✅ Logo + Text */
    .logo-wrapper {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      position: relative;
      top: -40px; /* keeps it above wave */
    }

    .gear-gif {
      width: 200px;
      height: auto;
      margin-bottom: 1rem;
    }

    h2 {
      font-family: var(--font-tertiary);
      font-weight: 900;
      color: var(--color-primary);
      font-size: clamp(2rem, 5vw, 2.8rem);
      letter-spacing: 2px;
    }

    /* ✅ Footer Wave */
    .loading-footer {
      position: absolute;
      bottom: 0;
      left: 0;
      width: 100%;
      overflow: hidden;
      line-height: 0;
    }

    /* Desktop SVG Wave (Fixed height, not too tall) */
    .svg-desktop {
      display: block;
      width: 100%;
      height: 180px; /* 👈 sets perfect height like before */
      max-height: 25vh;
    }

    /* Mobile SVG Wave (Deeper wave) */
    .svg-mobile {
      display: none;
      width: 100%;
      height: 220px;
    }

    /* Footer Overlay */
    .footer-overlay {
      position: absolute;
      bottom: 35px;
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
      line-height: 1.4;
    }

    /* ✅ Responsive Adjustments */
    @media (min-width: 1024px) {
      .gear-gif {
        width: 230px;
      }

      h2 {
        font-size: 2.7rem;
      }
    }

    @media (max-width: 768px) {
      .logo-wrapper {
        top: -20px;
      }

      .gear-gif {
        width: 150px;
      }

      h2 {
        font-size: 1.9rem;
      }

      /* Swap waves */
      .svg-desktop { display: none; }
      .svg-mobile { display: block; }
    }

    @media (max-width: 480px) {
      .logo-wrapper {
        top: -10px;
      }

      .gear-gif {
        width: 120px;
      }

      h2 {
        font-size: 1.6rem;
      }

      .footer-overlay p {
        font-size: 0.75rem;
      }
    }
  </style>
</head>

<body>
  <div id="loading-screen">
    <!-- ✅ LOGO -->
    <div class="logo-wrapper">
      <img src="../assets/components/gears.gif" alt="Loading..." class="gear-gif">
      <h2>JETZ MOTORPARTS</h2>
    </div>

    <!-- ✅ FOOTER WAVES -->
    <div class="loading-footer">
      <!-- Desktop SVG (Flatter Wave, Controlled Height) -->
      <svg class="svg-desktop" viewBox="0 0 500 180" preserveAspectRatio="none">
        <path d="M-5.92,50 C150.00,200.00 349.77,-70.00 500.00,60.00 L500.00,180.00 L0.00,180.00 Z"
              style="stroke: none; fill: var(--color-primary);"></path>
      </svg>

      <!-- Mobile SVG (Deeper Wave) -->
      <svg class="svg-mobile" viewBox="0 0 500 250" preserveAspectRatio="none">
        <path d="M0,120 C120,220 380,50 500,150 L500,250 L0,250 Z"
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
    }, 4000);
  </script>
</body>
</html>
