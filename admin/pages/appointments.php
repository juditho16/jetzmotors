<?php
require_once "../config/db.php";
require_once "../lib/functions.php";

/* Fetch summary data */
$stmt = $pdo->query("
    SELECT 
        a.id,
        CONCAT('APT-', LPAD(a.id, 4, '0')) AS ref_no,
        u.name AS customer,
        CONCAT(vb.name, ' ', vm.name, ' (', v.year, ')') AS vehicle,
        s.name AS service,
        a.date,
        a.status
    FROM appointments a
    LEFT JOIN users u ON a.user_id = u.id
    LEFT JOIN vehicles v ON a.vehicle_id = v.id
    LEFT JOIN vehicle_brands vb ON v.brand_id = vb.id
    LEFT JOIN vehicle_models vm ON v.model_id = vm.id
    LEFT JOIN services s ON a.service_id = s.id
    ORDER BY a.date DESC
");
$appointments = $stmt->fetchAll();

$pending   = count(array_filter($appointments, fn($a) => strtolower($a['status']) === 'pending'));
$confirmed = count(array_filter($appointments, fn($a) => strtolower($a['status']) === 'confirmed'));
$completed = count(array_filter($appointments, fn($a) => strtolower($a['status']) === 'completed'));
$cancelled = count(array_filter($appointments, fn($a) => strtolower($a['status']) === 'cancelled'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Appointments | Jetz Motors Admin</title>

<link rel="stylesheet" href="../../assets/css/app.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
.page-header {
  display:flex;
  justify-content:space-between;
  align-items:center;
  margin-bottom:1.2rem;
}

.tab-btn {
  background:none;
  border:none;
  font-weight:600;
  padding:.45rem .9rem;
  cursor:pointer;
  border-bottom:3px solid transparent;
  transition:.2s;
  color:var(--color-secondary);
}
.tab-btn.active {
  color:var(--color-primary);
  border-bottom:3px solid var(--color-primary);
}

/* Summary Cards */
.summary-cards {
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
  gap:1rem;
  margin-bottom:1.5rem;
}

.summary-card {
  background:var(--color-base-200);
  border-radius:var(--radius-box);
  padding:1.2rem;
  display:flex;
  gap:1rem;
  align-items:center;
  box-shadow:0 4px 12px rgba(0,0,0,0.08);
  transition:.2s;
}
.summary-card:hover { transform:translateY(-3px); }

.summary-icon { font-size:2rem; color:var(--color-primary); }
.summary-content h6 { margin:0; color:var(--color-secondary); font-weight:500; }
.summary-content h3 { margin:0; font-weight:700; }

.status-badge { padding:5px 12px; border-radius:50px; }
.status-pending { background:var(--color-warning); }
.status-confirmed { background:var(--color-info); color:#fff; }
.status-completed { background:var(--color-success); color:#fff; }
.status-cancelled { background:var(--color-error); color:#fff; }
</style>

</head>
<body>

<div class="container-fluid py-4">

  <!-- HEADER -->
  <div class="page-header">
    <h3><i class="bi bi-calendar-check me-2"></i> Appointments</h3>
  </div>

  <!-- SUMMARY CARDS -->
  <div class="summary-cards">
    <div class="summary-card">
      <i class="bi bi-clock summary-icon"></i>
      <div class="summary-content">
        <h6>Pending</h6>
        <h3><?= $pending ?></h3>
      </div>
    </div>
    <div class="summary-card">
      <i class="bi bi-check2-square summary-icon"></i>
      <div class="summary-content">
        <h6>Confirmed</h6>
        <h3><?= $confirmed ?></h3>
      </div>
    </div>
    <div class="summary-card">
      <i class="bi bi-check-circle summary-icon"></i>
      <div class="summary-content">
        <h6>Completed</h6>
        <h3><?= $completed ?></h3>
      </div>
    </div>
    <div class="summary-card">
      <i class="bi bi-x-circle summary-icon"></i>
      <div class="summary-content">
        <h6>Cancelled</h6>
        <h3><?= $cancelled ?></h3>
      </div>
    </div>
  </div>

  <!-- TAB BUTTONS -->
  <div class="d-flex gap-2 mb-3">
    <button id="tabAppointmentsBtn" class="tab-btn active">Appointments</button>
    <button id="tabServicesBtn" class="tab-btn">Manage Services</button>
  </div>

  <!-- TAB CONTENT 1 -->
  <div id="tabAppointments">
    <?php include "appointments_list.php"; ?>
  </div>

  <!-- TAB CONTENT 2 -->
  <div id="tabServices" style="display:none;">
    <?php include "manage_services.php"; ?>
  </div>

</div>

<!-- FINAL FIXED TAB SWITCH SCRIPT -->
<script>
const tabA = document.getElementById("tabAppointmentsBtn");
const tabB = document.getElementById("tabServicesBtn");

const contentA = document.getElementById("tabAppointments");
const contentB = document.getElementById("tabServices");

tabA.onclick = () => {
  tabA.classList.add("active");
  tabB.classList.remove("active");

  contentA.style.display = "block";
  contentB.style.display = "none";
};

tabB.onclick = () => {
  tabB.classList.add("active");
  tabA.classList.remove("active");

  contentA.style.display = "none";
  contentB.style.display = "block";
};
</script>

</body>
</html>
