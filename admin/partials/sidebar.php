<?php
$current = $_GET['page'] ?? 'dashboard';
function active($slug, $current)
{
    return $slug === $current ? 'active' : '';
}
?>
<div id="sidebar" class="sidebar d-flex flex-column flex-shrink-0 p-3 text-bg-dark"
    style="width:250px; min-height:100vh; position:fixed; transition:width 0.3s;">

    <!-- Shop name acts as toggle -->
    <div id="sidebarToggle" class="d-flex align-items-center mb-3" style="cursor:pointer;">
        <i class="bi bi-speedometer2 me-2 fs-5"></i>
        <span class="fs-5 fw-bold sidebar-text">Jetz Motors</span>
    </div>

    <hr>
    <ul class="nav nav-pills flex-column mb-auto gap-2">
        <li>
            <a href="index.php?page=dashboard" class="nav-link text-white <?= active('dashboard', $current) ?>">
                <i class="bi bi-house-door me-2"></i> <span class="sidebar-text">Dashboard</span>
            </a>
        </li>
        <li>
            <a href="index.php?page=pos" class="nav-link text-white <?= active('pos', $current) ?>">
                <i class="bi bi-cart4 me-2"></i> <span class="sidebar-text">POS</span>
            </a>
        </li>
        <li>
            <a href="index.php?page=appointments" class="nav-link text-white <?= active('appointments', $current) ?>">
                <i class="bi bi-calendar-event me-2"></i> <span class="sidebar-text">Appointments</span>
            </a>
        </li>
        <li>
            <a href="index.php?page=products" class="nav-link text-white <?= active('products', $current) ?>">
                <i class="bi bi-box-seam me-2"></i> <span class="sidebar-text">Products</span>
            </a>
        </li>
        <li>
            <a href="index.php?page=memberships" class="nav-link text-white <?= active('memberships', $current) ?>">
                <i class="bi bi-person-badge me-2"></i> <span class="sidebar-text">Memberships</span>
            </a>
        </li>
        <li>
            <a href="index.php?page=reports" class="nav-link text-white <?= active('reports', $current) ?>">
                <i class="bi bi-graph-up me-2"></i> <span class="sidebar-text">Reports</span>
            </a>
        </li>
    </ul>
    <hr>
    <div>
        <a href="logout.php" class="btn btn-outline-light w-100">
            <i class="bi bi-box-arrow-right"></i> <span class="sidebar-text">Logout</span>
        </a>
    </div>
</div>

<!-- Main content -->
<div id="content" class="content" style="margin-left:250px; padding:2rem; transition:margin-left 0.3s;">