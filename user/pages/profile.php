<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once "../config/db.php";

// Fetch logged-in user
$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Fetch membership (if exists)
$mStmt = $pdo->prepare("SELECT * FROM memberships WHERE user_id = ? AND status='active' LIMIT 1");
$mStmt->execute([$user_id]);
$membership = $mStmt->fetch();
?>

<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-body text-center position-relative">

            <!-- Logout icon on top-right -->
            <a href="loading.php" class="position-absolute top-0 end-0 m-3 text-danger" title="Logout" style="font-size: 1.3rem;">
                <i class="bi bi-box-arrow-right"></i>
            </a>

            <!-- Avatar -->
            <div class="mb-3 mt-2">
                <i class="bi bi-person-circle text-secondary" style="font-size: 5rem;"></i>
            </div>

            <!-- Name -->
            <h5 class="fw-bold mb-1"><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></h5>

            <!-- Membership -->
            <?php if ($membership): ?>
                <span class="badge bg-success mb-3">Member</span>
            <?php else: ?>
                <a href="index.php?page=memberships" class="btn btn-sm btn-primary mb-3">Apply for Membership</a>
            <?php endif; ?>

            <!-- User Info -->
            <div class="text-muted">
                <p class="mb-1"><i class="bi bi-envelope me-2"></i><?= htmlspecialchars($user['email']) ?></p>
                <p class="mb-1"><i class="bi bi-telephone me-2"></i><?= htmlspecialchars($user['phone']) ?></p>
                <p class="mb-0"><i class="bi bi-geo-alt me-2"></i><?= htmlspecialchars($user['address']) ?></p>
            </div>

        </div>
    </div>
</div>
