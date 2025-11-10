<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Jetz Motors User</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap + Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/app.css">

  <style>
    /* HEADER BASE */
    header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0.7rem 1.2rem;
      position: sticky;
      top: 0;
      z-index: 1000;
      background-color: var(--color-base-100);
      color: var(--color-base-content);
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
      transition: all 0.3s ease;
    }

    [data-theme="dark"] header {
      background-color: var(--color-primary);
      color: var(--color-primary-content);
    }

    /* BRAND */
    .brand {
      display: flex;
      align-items: center;
      gap: 0.6rem;
      font-family: var(--font-primary);
      font-weight: 600;
      font-size: 1rem;
      white-space: nowrap;
    }

    .brand img {
      width: 42px;
      height: 42px;
      object-fit: contain;
      transition: filter 0.3s ease;
      filter: brightness(70%) saturate(120%);
    }

    [data-theme="dark"] .brand img {
      filter: grayscale(100%) brightness(100%) contrast(150%) invert(1);
    }

    /* HEADER ACTIONS */
    .header-actions {
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .header-actions button {
      background: transparent;
      border: none;
      color: inherit;
      font-size: 1.4rem;
      position: relative;
      cursor: pointer;
      transition: color 0.3s ease;
    }

    .header-actions button:hover {
      color: var(--color-accent);
    }

    /* Notification Badge - clean, no pulse */
    .notif-badge {
      position: absolute;
      top: -3px;
      right: -3px;
      background: var(--color-error);
      color: var(--color-primary-content);
      border-radius: 50%;
      font-size: 0.6rem;
      padding: 3px 5px;
      font-weight: 600;
      box-shadow: 0 0 6px rgba(255, 0, 0, 0.5);
    }

    /* DESKTOP NAV (RIGHT SIDE) */
    .desktop-nav {
      display: none;
    }

    @media (min-width: 768px) {
      .desktop-nav {
        display: flex;
        align-items: center;
        gap: 1.4rem;
        margin-left: auto;
        margin-right: 2rem;
      }

      .desktop-nav a {
        color: inherit;
        text-decoration: none;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        transition: color 0.3s ease;
      }

      .desktop-nav a:hover {
        color: var(--color-accent);
      }
    }

    /* MOBILE BOTTOM NAV - FIXED CLEAN VERSION */
    .bottom-nav {
      position: fixed;
      bottom: 0;
      left: 0;
      width: 100%;
      background: var(--color-base-100);
      display: flex;
      justify-content: space-around;
      align-items: center;
      padding: 0.7rem 0;
      box-shadow: 0 -3px 10px rgba(0, 0, 0, 0.08);
      z-index: 999;
      transition: background-color 0.3s ease;
    }

    [data-theme="dark"] .bottom-nav {
      background: var(--color-primary);
    }

    .bottom-nav a {
      flex: 1;
      text-align: center;
      text-decoration: none;
      color: var(--color-primary);
      transition: color 0.3s ease;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }

    [data-theme="dark"] .bottom-nav a {
      color: var(--color-primary-content);
    }

    .bottom-nav a.active i {
      color: var(--color-accent);
      transform: translateY(-3px);
    }

    .bottom-nav i {
      font-size: 1.8rem;
      display: block;
      font-weight: 900;
      transition: all 0.3s ease;
    }

    .bottom-nav a:hover i {
      transform: translateY(-3px);
    }

    /* ✅ Hide labels on small screens */
    .bottom-nav span {
      display: inline-block;
      font-size: 0.8rem;
      margin-top: 3px;
    }

    @media (max-width: 768px) {
      .bottom-nav span {
        display: none !important;
      }
    }

    @media (min-width: 768px) {
      .bottom-nav {
        display: none;
      }
    }

    /* CONTENT AREA FIXES */
    main.container {
      max-width: 700px;
      margin: 0 auto;
      padding: 1rem;
    }

    @media (max-width: 768px) {
      main.container {
        padding-bottom: 90px; /* space for bottom nav */
        padding-top: 1.2rem; /* reduced top gap */
      }

      .card {
        margin: 0 auto;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        border: none;
      }
    }

  </style>
</head>

<body>
  <!-- HEADER -->
  <header>
    <div class="brand">
      <img src="../assets/components/gear2.gif" alt="Jetz Motors">
      <span>
        <?php
          echo isset($_SESSION['user_name'])
              ? 'Welcome, ' . htmlspecialchars(explode(' ', $_SESSION['user_name'])[0])
              : 'Welcome, User';
        ?>
      </span>
    </div>

    <nav class="desktop-nav">
      <a href="index.php?page=appointments"><i class="bi bi-calendar-event"></i> Book</a>
      <a href="index.php?page=purchase"><i class="bi bi-receipt"></i> Purchases</a>
      <a href="index.php?page=history"><i class="bi bi-clock-history"></i> History</a>
      <a href="index.php?page=profile"><i class="bi bi-person"></i> Profile</a>
    </nav>

    <div class="header-actions">
      <button type="button" data-bs-toggle="modal" data-bs-target="#notificationModal">
        <i class="bi bi-bell"></i>
        <span class="notif-badge">3</span>
      </button>
      <button id="themeToggle" title="Toggle Dark Mode">
        <i class="bi bi-moon"></i>
      </button>
    </div>
  </header>

  <!-- MOBILE NAV -->
  <nav class="bottom-nav">
    <a href="index.php?page=profile" class="active"><i class="bi bi-person"></i></a>
    <a href="index.php?page=history"><i class="bi bi-clock-history"></i></a>
    <a href="index.php?page=appointments"><i class="bi bi-calendar-event"></i></a>
    <a href="index.php?page=purchase"><i class="bi bi-receipt"></i></a>
  </nav>

  <!-- MODAL -->
  <div class="modal fade" id="notificationModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content p-3">
        <h5 class="mb-2">Notifications</h5>
        <p>No new notifications.</p>
      </div>
    </div>
  </div>

  <!-- SCRIPTS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const toggleBtn = document.getElementById("themeToggle");
    const icon = toggleBtn.querySelector("i");

    if (localStorage.getItem("theme") === "dark") {
      document.body.dataset.theme = "dark";
      icon.classList.replace("bi-moon", "bi-sun");
    }

    toggleBtn.addEventListener("click", () => {
      if (document.body.dataset.theme === "dark") {
        document.body.dataset.theme = "";
        localStorage.setItem("theme", "light");
        icon.classList.replace("bi-sun", "bi-moon");
      } else {
        document.body.dataset.theme = "dark";
        localStorage.setItem("theme", "dark");
        icon.classList.replace("bi-moon", "bi-sun");
      }
    });
  </script>
</body>
</html>
