<?php
require_once "../config/db.php";
require_once "../lib/functions.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Sales | Jetz Motors Admin</title>
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
    padding: 1.5rem;
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
    gap: 0.5rem;
    margin-bottom: 1.5rem;
  }

  .filters .search-box {
    position: relative;
    flex: 1;
    max-width: 300px;
  }

  .filters .search-box input {
    padding-left: 35px;
    border-radius: 50px;
  }

  .filters .search-box i {
    position: absolute;
    left: 12px;
    top: 9px;
    color: #888;
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
    padding: 0.35rem 0.75rem;
    font-size: 0.85rem;
  }

  .status-completed {
    background: var(--color-success);
    color: #fff;
  }

  .status-pending {
    background: var(--color-warning);
    color: #000;
  }

  .status-cancelled {
    background: var(--color-error);
    color: #fff;
  }

  .export-buttons button {
    border-radius: 50px;
    font-weight: 500;
  }
  </style>
</head>
<body>
<div class="container-fluid py-4">

  <div class="page-header">
    <h3><i class="bi bi-cash-stack me-2"></i>Sales Overview</h3>
    <div class="export-buttons">
      <button class="btn btn-sm btn-outline-primary me-2"><i class="bi bi-file-earmark-excel"></i> Export CSV</button>
      <button class="btn btn-sm btn-outline-danger"><i class="bi bi-file-earmark-pdf"></i> Export PDF</button>
    </div>
  </div>

  <!-- Summary Cards -->
  <div class="summary-cards mb-4">
    <div class="summary-card">
      <h6>Total Sales</h6>
      <h3>₱<?= number_format(253450.75, 2) ?></h3>
    </div>
    <div class="summary-card">
      <h6>Total Orders</h6>
      <h3>154</h3>
    </div>
    <div class="summary-card">
      <h6>Average Order Value</h6>
      <h3>₱<?= number_format(1646.42, 2) ?></h3>
    </div>
    <div class="summary-card">
      <h6>Best-Selling Product</h6>
      <h3>Yamaha Aerox</h3>
    </div>
  </div>

  <!-- Filters -->
  <div class="filters">
    <div class="search-box">
      <i class="bi bi-search"></i>
      <input type="text" id="searchInput" class="form-control" placeholder="Search sales...">
    </div>
    <select id="dateRange" class="form-select w-auto">
      <option value="today">Today</option>
      <option value="week">This Week</option>
      <option value="month">This Month</option>
      <option value="custom">Custom Range</option>
    </select>
  </div>

  <!-- Sales Table -->
  <div class="table-responsive">
    <table class="table table-hover align-middle">
      <thead>
        <tr>
          <th>Ref No</th>
          <th>Customer</th>
          <th>Payment Type</th>
          <th>Total</th>
          <th>Date</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody id="salesTableBody">
        <tr>
          <td>#TXN-00124</td>
          <td>Juan Dela Cruz</td>
          <td>Cash</td>
          <td>₱12,450.00</td>
          <td>Nov 10, 2025</td>
          <td><span class="status-badge status-completed">Completed</span></td>
        </tr>
        <tr>
          <td>#TXN-00125</td>
          <td>Maria Santos</td>
          <td>Installment</td>
          <td>₱8,950.00</td>
          <td>Nov 11, 2025</td>
          <td><span class="status-badge status-pending">Pending</span></td>
        </tr>
        <tr>
          <td>#TXN-00126</td>
          <td>Carlos Garcia</td>
          <td>Card</td>
          <td>₱18,000.00</td>
          <td>Nov 12, 2025</td>
          <td><span class="status-badge status-completed">Completed</span></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Live search
document.getElementById("searchInput").addEventListener("input", function() {
  const query = this.value.toLowerCase();
  document.querySelectorAll("#salesTableBody tr").forEach(row => {
    const match = row.innerText.toLowerCase().includes(query);
    row.style.display = match ? "" : "none";
  });
});
</script>
</body>
</html>
