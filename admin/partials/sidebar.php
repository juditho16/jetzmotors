<?php
$current = $_GET['page'] ?? 'dashboard';
function active($slug, $current)
{
    return $slug === $current ? 'active' : '';
}
?>
<link rel="stylesheet" href="../assets/css/app.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
/* Sidebar Base Styling */
.sidebar {
  width: 250px;
  min-height: 100vh;
  background-color: var(--color-primary);
  color: var(--color-primary-content);
  position: fixed;
  left: 0;
  top: 0;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  padding: 1.5rem 1rem;
  transition: all 0.3s ease;
  box-shadow: 4px 0 15px rgba(0, 0, 0, 0.1);
  border-radius: 0 1rem 1rem 0;
}

/* Brand section */
.sidebar .brand {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  margin-bottom: 1.2rem;
}

/* Logo (GIF) styling */
/* Logo (GIF) styling */
.sidebar .brand img {
  display: block;
  width: 80px;
  height: 80px;
  object-fit: contain;
  margin: 0 auto;
transition: transform 0.3s ease;
}

.sidebar .brand img:hover {
  transform: rotate(8deg) scale(1.05);
}

/* Brand text (remove extra gap) */
.sidebar .brand span {
  font-family: var(--font-primary);
  font-weight: 700;
  font-size: 1.1rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  color: var(--color-primary-content);
  margin-top: 0.2rem; /* minimal spacing */
}

/* Navigation */
.sidebar .nav {
  display: flex;
  flex-direction: column;
  gap: 0.3rem;
}

.sidebar .nav-link {
  display: flex;
  align-items: center;
  padding: 0.7rem 1rem;
  color: var(--color-primary-content);
  border-radius: var(--radius-box);
  text-decoration: none;
  transition: background 0.3s, color 0.3s, transform 0.2s;
  font-family: var(--font-secondary);
  font-weight: 500;
}

.sidebar .nav-link i {
  font-size: 1.1rem;
  margin-right: 0.6rem;
}

/* Hover + Active share same accent */
.sidebar .nav-link:hover,
.sidebar .nav-link.active {
  background-color: var(--color-accent) !important;
  color: var(--color-base-content) !important;
  transform: translateX(3px);
  font-weight: 600;
}

/* Logout button */
.sidebar .logout-btn {
  margin-top: 1rem;
  padding: 0.6rem 1rem;
  text-align: center;
  border-radius: var(--radius-box);
  border: 2px solid var(--color-primary-content);
  color: var(--color-primary-content);
  background: transparent;
  transition: 0.3s ease;
}

.sidebar .logout-btn:hover {
  background-color: var(--color-error);
  color: var(--color-primary);
  transform: translateY(-1px);
}

/* Responsive adjustments */
@media (max-width: 992px) {
  .sidebar {
    width: 200px;
    border-radius: 0;
  }
  #content {
    margin-left: 200px;
  }
}

@media (max-width: 768px) {
  .sidebar {
    width: 100%;
    height: auto;
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
    border-radius: 0;
    padding: 1rem;
  }
  .sidebar .brand {
    flex-direction: row;
    align-items: center;
    text-align: left;
  }
  .sidebar .brand img {
    width: 50px;
    height: 50px;
    margin: 0 0.6rem 0 0;
  }
  .sidebar .nav {
    flex-direction: row;
    gap: 0.5rem;
  }
  #content {
    margin-left: 0;
    padding-top: 4.5rem;
  }
}
</style>

<!-- Sidebar -->
<div id="sidebar" class="sidebar">
  <div>
    <!-- Centered Logo and Brand -->
    <div class="brand">
      <img src="../assets/components/management.gif" alt="Jetz Motors Logo">
      <span>JETZ MOTORS</span>
    </div>

    <hr>

    <ul class="nav flex-column mb-auto">
      <li>
        <a href="index.php?page=dashboard" class="nav-link <?= active('dashboard', $current) ?>">
          <i class="bi bi-house-door"></i>
          <span>Dashboard</span>
        </a>
      </li>
      <li>
        <a href="index.php?page=pos" class="nav-link <?= active('pos', $current) ?>">
          <i class="bi bi-cart4"></i>
          <span>POS</span>
        </a>
      </li>
      <li>
        <a href="index.php?page=sales" class="nav-link <?= active('sales', $current) ?>">
          <i class="bi bi-cash-stack"></i>
          <span>Sales</span>
        </a>
      </li>
      <li>
        <a href="index.php?page=appointments" class="nav-link <?= active('appointments', $current) ?>">
          <i class="bi bi-calendar-event"></i>
          <span>Appointments</span>
        </a>
      </li>
      <li>
        <a href="index.php?page=inventory" class="nav-link <?= active('inventory', $current) ?>">
          <i class="bi bi-box-seam"></i>
          <span>Inventory</span>
        </a>
      </li>
      <li>
        <a href="index.php?page=memberships" class="nav-link <?= active('memberships', $current) ?>">
          <i class="bi bi-person-badge"></i>
          <span>Memberships</span>
        </a>
      </li>
      <li>
        <a href="index.php?page=reports" class="nav-link <?= active('reports', $current) ?>">
          <i class="bi bi-graph-up"></i>
          <span>Reports</span>
        </a>
      </li>
    </ul>
  </div>

  <div>
    <a href="logout.php" class="logout-btn d-block">
      <i class="bi bi-box-arrow-right me-1"></i> Logout
    </a>
  </div>
</div>

<!-- Main Content -->
<div id="content" class="content" style="margin-left:250px; padding:2rem; transition:margin-left 0.3s;">
