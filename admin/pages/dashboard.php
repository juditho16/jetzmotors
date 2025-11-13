<?php
require_once "../config/db.php";
require_once "../lib/functions.php";

// Mock data (replace with DB later)
$totalSales = 325000.50;
$totalOrders = 154;
$totalCustomers = 87;
$topProduct = "Yamaha Aerox";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard | Jetz Motors Admin</title>
  <link rel="stylesheet" href="../../assets/css/app.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <style>
  .page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
  }

  .summary-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
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

  /* === GRID LAYOUT FOR CHARTS === */
  .dashboard-grid {
    display: grid;
    grid-template-columns: 30% 30% 40%;
    grid-template-rows: auto 1fr;
    gap: 1rem;
    margin-top: 2rem;
  }

  .chart-card {
    background: var(--color-base-200);
    border-radius: var(--radius-box);
    padding: 1rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    height: 100%;
  }

  .chart-card h6 {
    font-weight: 600;
    margin-bottom: 0.8rem;
  }

  /* Daily Sales */
  .daily-sales {
    grid-column: 1 / 2;
    grid-row: 1 / 2;
  }

  /* Booking Schedule Pie Chart */
  .booking-schedule {
    grid-column: 2 / 3;
    grid-row: 1 / 2;
  }

  /* POS Transactions (Tall Card spanning two rows) */
  .recent-pos {
    grid-column: 3 / 4;
    grid-row: 1 / 3;
    overflow-y: auto;
    max-height: 650px;
  }

  /* Weekly Revenue Comparison (Wide bottom chart) */
  .weekly-revenue {
    grid-column: 1 / 3;
    grid-row: 2 / 3;
  }

  th {
    background-color: var(--color-primary);
    color: var(--color-primary-content);
  }

  tbody tr:hover {
    background-color: var(--color-base-300);
  }

  .status-badge {
    border-radius: 30px;
    padding: 0.35rem 0.75rem;
    font-size: 0.8rem;
    font-weight: 600;
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

  @media (max-width: 992px) {
    .dashboard-grid {
      grid-template-columns: 1fr;
      grid-template-rows: auto;
    }
    .recent-pos {
      grid-row: auto;
      max-height: none;
    }
  }
  </style>
</head>
<body>
<div class="container-fluid py-4">
  <div class="page-header">
    <h3><i class="bi bi-speedometer2 me-2"></i>Dashboard Overview</h3>
    <button class="btn btn-sm btn-primary"><i class="bi bi-arrow-clockwise me-1"></i> Refresh</button>
  </div>

  <!-- Summary Cards -->
  <div class="summary-cards mb-4">
    <div class="summary-card">
      <h6>Total Sales</h6>
      <h3>₱<?= number_format($totalSales, 2) ?></h3>
    </div>
    <div class="summary-card">
      <h6>Total Orders</h6>
      <h3><?= $totalOrders ?></h3>
    </div>
    <div class="summary-card">
      <h6>Total Customers</h6>
      <h3><?= $totalCustomers ?></h3>
    </div>
    <div class="summary-card">
      <h6>Top Product</h6>
      <h3><?= $topProduct ?></h3>
    </div>
  </div>

  <!-- Analytics Dashboard Grid -->
  <div class="dashboard-grid">
    <!-- 1️⃣ Daily Sales Trend -->
    <div class="chart-card daily-sales">
      <h6><i class="bi bi-bar-chart-line me-1"></i> Daily Sales Trend</h6>
      <canvas id="dailySalesChart" height="150"></canvas>
    </div>

    <!-- 2️⃣ Booking Schedule Pie -->
    <div class="chart-card booking-schedule">
      <h6><i class="bi bi-pie-chart me-1"></i> Booking Schedule Status</h6>
      <canvas id="bookingScheduleChart" height="150"></canvas>
    </div>

    <!-- 3️⃣ Recent POS Transactions (Spanning 2 Rows) -->
    <div class="chart-card recent-pos">
      <h6><i class="bi bi-receipt me-2"></i> Recent POS Transactions</h6>
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead>
            <tr>
              <th>Ref No</th>
              <th>Customer</th>
              <th>Total</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>#TXN-101</td>
              <td>Juan Dela Cruz</td>
              <td>₱18,500.00</td>
              <td><span class="status-badge status-completed">Completed</span></td>
            </tr>
            <tr>
              <td>#TXN-102</td>
              <td>Maria Santos</td>
              <td>₱23,900.00</td>
              <td><span class="status-badge status-pending">Pending</span></td>
            </tr>
            <tr>
              <td>#TXN-103</td>
              <td>Mark Reyes</td>
              <td>₱9,850.00</td>
              <td><span class="status-badge status-completed">Completed</span></td>
            </tr>
            <tr>
              <td>#TXN-104</td>
              <td>Carlos Diaz</td>
              <td>₱6,450.00</td>
              <td><span class="status-badge status-cancelled">Cancelled</span></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- 4️⃣ Weekly Revenue Comparison -->
    <div class="chart-card weekly-revenue">
      <h6><i class="bi bi-graph-up-arrow me-1"></i> Weekly Revenue Comparison</h6>
      <canvas id="weeklyRevenueChart" height="150"></canvas>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Daily Sales Bar Chart
new Chart(document.getElementById('dailySalesChart'), {
  type: 'bar',
  data: {
    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
    datasets: [{
      label: '₱ Sales',
      data: [24000, 31000, 28000, 36000, 41000, 47000, 39000],
      backgroundColor: 'oklch(77% 0.152 181.912)',
      borderRadius: 6
    }]
  },
  options: {
    plugins: { legend: { display: false } },
    scales: { y: { beginAtZero: true } }
  }
});

// Booking Schedule Pie Chart
new Chart(document.getElementById('bookingScheduleChart'), {
  type: 'pie',
  data: {
    labels: ['Completed', 'Pending', 'Cancelled'],
    datasets: [{
      data: [55, 30, 15],
      backgroundColor: [
        'oklch(76% 0.177 163.223)',
        'oklch(82% 0.189 84.429)',
        'oklch(71% 0.194 13.428)'
      ]
    }]
  },
  options: {
    plugins: { legend: { position: 'bottom' } }
  }
});

// Weekly Revenue Line Chart
new Chart(document.getElementById('weeklyRevenueChart'), {
  type: 'line',
  data: {
    labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
    datasets: [
      {
        label: 'This Month',
        data: [120000, 145000, 132000, 155000],
        borderColor: 'oklch(77% 0.152 181.912)',
        tension: 0.4,
        fill: false
      },
      {
        label: 'Last Month',
        data: [100000, 118000, 124000, 135000],
        borderColor: 'oklch(82% 0.189 84.429)',
        borderDash: [5,5],
        tension: 0.4,
        fill: false
      }
    ]
  },
  options: {
    plugins: { legend: { position: 'bottom' } },
    scales: { y: { beginAtZero: true } }
  }
});
</script>
</body>
</html>
