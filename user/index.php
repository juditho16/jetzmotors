<?php
session_start();
require_once "../config/db.php";
require_once "../lib/functions.php";

// ✅ Redirect to login if not authenticated
if (empty($_SESSION['user_logged'])) {
    header("Location: login.php");
    exit;
}

// ✅ Include header
include __DIR__ . "/partials/header.php";

// ✅ Load page
$page = $_GET['page'] ?? 'profile';
$pageFile = __DIR__ . "/pages/" . basename($page) . ".php";

// ✅ Load page if it exists
if (file_exists($pageFile)) {
    include $pageFile;
} else {
    echo "<div class='alert alert-danger text-center mt-5'>⚠️ Page not found: {$pageFile}</div>";
}

// ✅ Include navbar
include __DIR__ . "/partials/navbar.php";
?>

<!-- Notifications Modal -->
<div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title" id="notificationModalLabel"><i class="bi bi-bell me-2"></i> Notifications</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <ul class="list-group">
          <li class="list-group-item d-flex justify-content-between align-items-center">
            Your service booking for <b>Oil Change</b> is confirmed.
            <span class="badge bg-primary">New</span>
          </li>
          <li class="list-group-item">
            Your invoice <b>INV-1001</b> has been marked as <span class="text-success">Paid</span>.
          </li>
          <li class="list-group-item">
            Membership expiring on <b>Dec 31, 2025</b>. Renew to keep 3% discount.
          </li>
        </ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
