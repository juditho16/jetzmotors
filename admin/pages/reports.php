<?php
// Daily revenue
$daily = $pdo->query("
  SELECT DATE(created_at) AS day, SUM(total) AS revenue
  FROM invoices
  GROUP BY DATE(created_at)
  ORDER BY day DESC
  LIMIT 7
")->fetchAll();

// Discounts summary
$discounts = $pdo->query("
  SELECT SUM(discount) AS total_discount
  FROM invoices
")->fetch()['total_discount'] ?? 0;
?>

<div class="container-fluid">
  <h1 class="mb-4"><i class="bi bi-graph-up me-2"></i> Reports</h1>

  <div class="row g-3 mb-4">
    <div class="col-md-4">
      <div class="card text-bg-info">
        <div class="card-body">
          <h5 class="card-title">Total Discounts Given</h5>
          <p class="card-text fs-3"><?= fmt_money($discounts) ?></p>
        </div>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header bg-dark text-white">Last 7 Days Revenue</div>
    <div class="card-body">
      <canvas id="revenueChart"></canvas>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('revenueChart'), {
  type: 'bar',
  data: {
    labels: <?= json_encode(array_column($daily, 'day')) ?>,
    datasets: [{
      label: 'Revenue (₱)',
      data: <?= json_encode(array_map(fn($d)=> (float)$d['revenue'], $daily)) ?>,
      backgroundColor: 'rgba(13,110,253,0.7)'
    }]
  }
});
</script>
