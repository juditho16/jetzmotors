<?php
// Fetch logged-in user
$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Fetch membership (if exists)
$membership = null;
$mStmt = $pdo->prepare("SELECT * FROM memberships WHERE user_id = ? AND status='active' LIMIT 1");
$mStmt->execute([$user_id]);
$membership = $mStmt->fetch();
?>

<div class="container mt-3">
    <h4><i class="bi bi-person-circle me-2"></i> My Profile</h4>

    <div class="card mt-3">
        <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($user['first_name'] . " " . $user['last_name']) ?></h5>
            <p class="mb-1"><i class="bi bi-envelope"></i> <?= htmlspecialchars($user['email']) ?></p>
            <p class="mb-1"><i class="bi bi-telephone"></i> <?= htmlspecialchars($user['phone']) ?></p>
            <p class="mb-1"><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($user['address']) ?></p>
        </div>
    </div>

    <!-- Membership -->
    <div class="card mt-3">
        <div class="card-header bg-dark text-white">
            Membership
        </div>
        <div class="card-body">
            <?php if ($membership): ?>
                <p>Status: <span class="badge bg-success">Active</span></p>
                <p>Valid Until: <b><?= htmlspecialchars($membership['expiry_date']) ?></b></p>
                <p>Discount: <b>3% on services</b></p>
            <?php else: ?>
                <p>Status: <span class="badge bg-secondary">Not a member</span></p>
                <a href="index.php?page=memberships" class="btn btn-primary btn-sm">Become a Member</a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Logout -->
    <div class="mt-3">
        <a href="logout.php" class="btn btn-danger w-100">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </div>
</div>