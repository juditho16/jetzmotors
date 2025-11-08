<?php
session_start();
require_once "../config/db.php";
require_once "../lib/functions.php";

// Guard admin session
if (empty($_SESSION['admin_logged'])) {
    header("Location: admin.php");
    exit;
}

include "partials/header.php";
include "partials/sidebar.php";

$page = $_GET['page'] ?? 'dashboard';
$pageFile = __DIR__ . "/pages/" . basename($page) . ".php";

if (file_exists($pageFile)) {
    include $pageFile;
} else {
    echo "<div class='alert alert-danger'>Page not found.</div>";
}

echo "</div>"; // close content
?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Sidebar toggle script -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const sidebar = document.getElementById('sidebar');
        const content = document.getElementById('content');
        const toggle = document.getElementById('sidebarToggle');

        // ✅ Load saved state from localStorage
        if (localStorage.getItem("sidebar-collapsed") === "true") {
            sidebar.classList.add("collapsed");
            content.classList.add("collapsed");
        }

        if (toggle) {
            toggle.addEventListener('click', function () {
                sidebar.classList.toggle('collapsed');
                content.classList.toggle('collapsed');

                // ✅ Save state to localStorage
                if (sidebar.classList.contains("collapsed")) {
                    localStorage.setItem("sidebar-collapsed", "true");
                } else {
                    localStorage.setItem("sidebar-collapsed", "false");
                }
            });
        }
    });
</script>
</body>

</html>