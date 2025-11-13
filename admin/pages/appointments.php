<?php
require_once "../config/db.php";
require_once "../lib/functions.php";

// Mock Data (replace later with DB query)
$appointments = [
  ["ref_no" => "APT-001", "customer" => "Juan Dela Cruz", "vehicle" => "Yamaha Aerox", "service" => "Engine Check-up", "date" => "2025-11-13", "status" => "Pending"],
  ["ref_no" => "APT-002", "customer" => "Maria Santos", "vehicle" => "Honda Click 125i", "service" => "Oil Change", "date" => "2025-11-14", "status" => "Approved"],
  ["ref_no" => "APT-003", "customer" => "Mark Reyes", "vehicle" => "Suzuki Raider 150", "service" => "Tire Replacement", "date" => "2025-11-15", "status" => "Completed"],
  ["ref_no" => "APT-004", "customer" => "Carlos Diaz", "vehicle" => "Kawasaki Rouser 200NS", "service" => "Transmission Repair", "date" => "2025-11-16", "status" => "Cancelled"],
];

// Count summary
$pending = count(array_filter($appointments, fn($a) => strtolower($a['status']) === 'pending'));
$approved = count(array_filter($appointments, fn($a) => strtolower($a['status']) === 'approved'));
$completed = count(array_filter($appointments, fn($a) => strtolower($a['status']) === 'completed'));
$cancelled = count(array_filter($appointments, fn($a) => strtolower($a['status']) === 'cancelled'));
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Appointments | Jetz Motors Admin</title>
  <link rel="stylesheet" href="../../assets/css/app.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
  .page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
  }

  .summary-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 1rem;
  }

  .summary-card {
    background: var(--color-base-200);
    border-radius: var(--radius-box);
    padding: 1.3rem;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    transition: transform 0.2s ease;
  }

  .summary-card:hover {
    transform: translateY(-3px);
  }

  .summary-card h6 {
    color: var(--color-secondary);
    font-weight: 500;
  }

  .summary-card h3 {
    margin-top: 0.4rem;
    font-weight: 700;
    font-family: var(--font-primary);
  }

  .filters {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 0.8rem;
    margin: 1.5rem 0;
  }

  .filters .search-box {
    position: relative;
    flex: 1;
    max-width: 300px;
  }

  .filters .search-box input {
    padding-left: 35px;
    border-radius: 50px;
    background: var(--color-base-200);
    border: 1px solid var(--color-base-300);
  }

  .filters .search-box i {
    position: absolute;
    left: 12px;
    top: 9px;
    color: var(--color-secondary);
  }

  table {
    border-radius: var(--radius-box);
    overflow: hidden;
  }

  th {
    background-color: var(--color-primary);
    color: var(--color-primary-content);
    font-weight: 500;
  }

  tbody tr:hover {
    background-color: var(--color-base-300);
  }

  .status-badge {
    border-radius: 30px;
    padding: 0.3rem 0.8rem;
    font-size: 0.85rem;
    font-weight: 600;
  }

  .status-pending { background: var(--color-warning); color: #000; }
  .status-approved { background: var(--color-info); color: #fff; }
  .status-completed { background: var(--color-success); color: #fff; }
  .status-cancelled { background: var(--color-error); color: #fff; }

  .action-buttons i {
    cursor: pointer;
    margin: 0 6px;
    transition: 0.2s ease;
  }

  .action-buttons i:hover {
    transform: scale(1.2);
  }

  .btn-add {
    background: var(--color-accent);
    color: var(--color-base-content);
    border-radius: 50px;
    border: none;
  }

  .btn-add:hover {
    background: var(--color-primary);
    color: var(--color-primary-content);
  }
  </style>
</head>
<body>
<div class="container-fluid py-4">

  <!-- HEADER -->
  <div class="page-header">
    <h3><i class="bi bi-calendar-check me-2"></i> Service Repair Appointments</h3>
    <button class="btn btn-add">
      <i class="bi bi-plus-circle me-1"></i> Add Appointment
    </button>
  </div>

  <!-- SUMMARY CARDS -->
  <div class="summary-cards mb-4">
    <div class="summary-card">
      <h6>Pending</h6>
      <h3><?= $pending ?></h3>
    </div>
    <div class="summary-card">
      <h6>Approved</h6>
      <h3><?= $approved ?></h3>
    </div>
    <div class="summary-card">
      <h6>Completed</h6>
      <h3><?= $completed ?></h3>
    </div>
    <div class="summary-card">
      <h6>Cancelled</h6>
      <h3><?= $cancelled ?></h3>
    </div>
  </div>

  <!-- FILTERS -->
  <div class="filters">
    <div class="search-box">
      <i class="bi bi-search"></i>
      <input type="text" id="searchInput" class="form-control" placeholder="Search appointment...">
    </div>
    <select id="statusFilter" class="form-select w-auto">
      <option value="">All Status</option>
      <option>Pending</option>
      <option>Approved</option>
      <option>Completed</option>
      <option>Cancelled</option>
    </select>
  </div>

  <!-- TABLE -->
  <div class="table-responsive">
    <table class="table table-hover align-middle text-center">
      <thead>
        <tr>
          <th>Reference No</th>
          <th>Customer</th>
          <th>Vehicle</th>
          <th>Service Type</th>
          <th>Appointment Date</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody id="appointmentTable">
      <?php foreach ($appointments as $apt): ?>
        <?php
          $statusClass = match (strtolower($apt['status'])) {
            'pending' => 'status-pending',
            'approved' => 'status-approved',
            'completed' => 'status-completed',
            'cancelled' => 'status-cancelled',
            default => ''
          };
        ?>
        <tr>
          <td><?= htmlspecialchars($apt['ref_no']) ?></td>
          <td><?= htmlspecialchars($apt['customer']) ?></td>
          <td><?= htmlspecialchars($apt['vehicle']) ?></td>
          <td><?= htmlspecialchars($apt['service']) ?></td>
          <td><?= date('M d, Y', strtotime($apt['date'])) ?></td>
          <td><span class="status-badge <?= $statusClass ?>"><?= htmlspecialchars($apt['status']) ?></span></td>
          <td class="action-buttons">
            <i class="bi bi-eye text-info"></i>
            <i class="bi bi-check-circle text-success"></i>
            <i class="bi bi-x-circle text-danger"></i>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Live search
document.getElementById("searchInput").addEventListener("input", function() {
  const query = this.value.toLowerCase();
  document.querySelectorAll("#appointmentTable tr").forEach(row => {
    const text = row.innerText.toLowerCase();
    row.style.display = text.includes(query) ? "" : "none";
  });
});

// Status filter
document.getElementById("statusFilter").addEventListener("change", function() {
  const filter = this.value.toLowerCase();
  document.querySelectorAll("#appointmentTable tr").forEach(row => {
    const status = row.children[5].innerText.toLowerCase();
    row.style.display = !filter || status.includes(filter) ? "" : "none";
  });
});
</script>
</body>
</html>
