<?php
require_once "../config/db.php";
require_once "../lib/functions.php";

// Fetch members
$stmt = $pdo->query("
  SELECT u.*, m.discount_rate, m.status, m.start_date, m.end_date 
  FROM users u 
  LEFT JOIN memberships m ON u.id = m.user_id 
  WHERE u.is_member = 1
  ORDER BY m.end_date DESC
");
$members = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Memberships | Admin Panel</title>
  <link rel="stylesheet" href="../../assets/css/app.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    .page-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.5rem;
    }
    .btn-outline-primary {
      background: transparent;
      color: var(--color-primary);
      border: 2px solid var(--color-primary);
      font-weight: 600;
      transition: all 0.2s ease;
    }
    .btn-outline-primary:hover {
      background: var(--color-primary);
      color: var(--color-primary-content);
      transform: translateY(-1px);
    }
    .top-controls {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      margin-bottom: 1.5rem;
    }
    .tab-controls {
      display: flex;
      gap: 1rem;
      align-items: center;
    }
    .tab-btn {
      background: none;
      border: none;
      font-family: var(--font-primary);
      font-weight: 600;
      cursor: pointer;
      padding: 0.5rem 1rem;
      border-bottom: 3px solid transparent;
      transition: 0.2s;
      color: var(--color-secondary);
    }
    .tab-btn.active {
      color: var(--color-primary);
      border-bottom: 3px solid var(--color-primary);
    }
    .search-box {
      position: relative;
    }
    .search-box input {
      padding-left: 2rem;
      border-radius: 30px;
      border: 1px solid var(--color-base-300);
      width: 260px;
      background: var(--color-base-200);
    }
    .search-box i {
      position: absolute;
      left: 10px;
      top: 9px;
      color: var(--color-secondary);
    }
    .member-card {
      border-radius: 1rem;
      transition: transform 0.2s ease, box-shadow 0.3s ease;
      border: 1px solid var(--color-base-300);
      background: var(--color-base-200);
    }
    .member-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    .member-avatar {
      width: 70px;
      height: 70px;
      border-radius: 50%;
      background: var(--color-accent);
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-weight: bold;
      font-size: 24px;
      margin: 0 auto;
    }
    .badge-member {
      background: var(--color-primary);
      color: var(--color-primary-content);
      font-size: 0.75rem;
      border-radius: 30px;
      padding: 0.25rem 0.75rem;
      margin-top: 0.4rem;
    }
    .stats-card {
      background: var(--color-base-300);
      border-radius: 0.8rem;
      display: flex;
      justify-content: space-between;
      padding: 0.7rem 1rem;
      margin-top: 0.8rem;
    }
  </style>
</head>
<body>
<div class="container-fluid py-4">

  <div class="page-header">
    <h3><i class="bi bi-person-badge me-2"></i> Memberships</h3>
    <button class="btn-outline-primary" data-bs-toggle="modal" data-bs-target="#registerMemberModal">
      <i class="bi bi-person-plus me-1"></i> Register New Member
    </button>
  </div>

  <div class="top-controls">
    <div class="tab-controls">
      <button id="tabUsers" class="tab-btn">Users</button>
      <button id="tabMembers" class="tab-btn active">Members</button>
    </div>
    <div class="search-box">
      <i class="bi bi-search"></i>
      <input type="text" id="searchInput" placeholder="Search members...">
    </div>
  </div>

  <div id="memberContainer" class="row g-3">
    <?php foreach ($members as $m): ?>
      <?php
        $name = sanitize($m['name']);
        $initials = strtoupper(substr($name, 0, 2));
        $status = $m['status'] ?? 'Active';
        $end_date = $m['end_date'] ? date("M d, Y", strtotime($m['end_date'])) : 'N/A';
        $loyaltyPoints = rand(20, 400);
        $discountAvailed = number_format($m['discount_rate'] ?? 3, 2) . "%";
      ?>
      <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 member-card-container" data-name="<?= strtolower($name) ?>">
        <div class="card member-card text-center p-3">
          <div class="member-avatar mb-2"><?= $initials ?></div>
          <h6 class="mb-1"><?= $name ?></h6>
          <span class="badge-member"><?= $status ?></span>
          <div class="stats-card mt-2">
            <div>
              <small>Loyalty</small><br>
              <strong><?= $loyaltyPoints ?></strong>
            </div>
            <div>
              <small>Discount</small><br>
              <strong><?= $discountAvailed ?></strong>
            </div>
          </div>
          <div class="mt-2">
            <small>Valid Until</small><br>
            <strong><?= $end_date ?></strong>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<?php include "../modals/register_member_modal.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById("searchInput").addEventListener("input", function() {
  const query = this.value.toLowerCase();
  document.querySelectorAll(".member-card-container").forEach(card => {
    const name = card.getAttribute("data-name");
    card.style.display = name.includes(query) ? "block" : "none";
  });
});
document.getElementById("tabUsers").onclick = () => window.location.href = "users.php";
document.getElementById("tabMembers").onclick = () => window.location.href = "memberships.php";
</script>
</body>
</html>
