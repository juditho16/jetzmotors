<?php
// /admin/pages/dashboard.php

// Total users
$totalUsers = $pdo->query("SELECT COUNT(*) AS c FROM users")->fetch()['c'] ?? 0;

// Total active members
$totalMembers = $pdo->query("SELECT COUNT(*) AS c FROM users WHERE is_member=1 AND (member_until IS NULL OR member_until >= CURDATE())")->fetch()['c'] ?? 0;

// Today's appointments
$todayAppointments = $pdo->prepare("SELECT COUNT(*) AS c FROM appointments WHERE DATE(schedule_at)=CURDATE()");
$todayAppointments->execute();
$todayAppointments = $todayAppointments->fetch()['c'] ?? 0;

// Revenue today
$todayRevenue = $pdo->prepare("SELECT SUM(total) AS total FROM invoices WHERE DATE(created_at)=CURDATE()");
$todayRevenue->execute();
$todayRevenue = $todayRevenue->fetch()['total'] ?? 0;

// Recent appointments
$recentAppointments = $pdo->query("
  SELECT a.id, u.name AS customer, s.name AS service, a.status, a.schedule_at 
  FROM appointments a
  JOIN users u ON a.user_id=u.id
  JOIN services s ON a.service_id=s.id
  ORDER BY a.created_at DESC
  LIMIT 5
")->fetchAll();
?>

<div class="container-fluid">
    <h1 class="mb-4"><i class="bi bi-speedometer2 me-2"></i> Dashboard</h1>

    <!-- Quick stats -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card text-bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Total Customers</h5>
                    <p class="card-text fs-3"><?= $totalUsers ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-success">
                <div class="card-body">
                    <h5 class="card-title">Active Members</h5>
                    <p class="card-text fs-3"><?= $totalMembers ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-warning">
                <div class="card-body">
                    <h5 class="card-title">Appointments Today</h5>
                    <p class="card-text fs-3"><?= $todayAppointments ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-danger">
                <div class="card-body">
                    <h5 class="card-title">Revenue Today</h5>
                    <p class="card-text fs-3"><?= fmt_money($todayRevenue ?? 0) ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Appointments -->
    <div class="card mb-4">
        <div class="card-header bg-dark text-white">
            <i class="bi bi-clock-history me-2"></i> Recent Appointments
        </div>
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Service</th>
                        <th>Status</th>
                        <th>Schedule</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($recentAppointments): ?>
                        <?php foreach ($recentAppointments as $a): ?>
                            <tr>
                                <td>#<?= $a['id'] ?></td>
                                <td><?= sanitize($a['customer']) ?></td>
                                <td><?= sanitize($a['service']) ?></td>
                                <td>
                                    <?php
                                    $badgeClass = match ($a['status']) {
                                        'Pending' => 'warning',
                                        'In Progress' => 'info',
                                        'Completed' => 'success',
                                        'Cancelled' => 'danger',
                                        default => 'secondary'
                                    };
                                    ?>
                                    <span class="badge bg-<?= $badgeClass ?>"><?= $a['status'] ?></span>
                                </td>
                                <td><?= date("M d, Y h:i A", strtotime($a['schedule_at'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">No recent appointments.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Revenue Chart -->
    <div class="card">
        <div class="card-header bg-dark text-white">
            <i class="bi bi-graph-up-arrow me-2"></i> Weekly Revenue
        </div>
        <div class="card-body">
            <canvas id="revenueChart" height="100"></canvas>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode(
                array_map(fn($i) => date("D", strtotime("-$i day")), range(6, 0))
            ) ?>,
            datasets: [{
                label: 'Revenue (₱)',
                data: <?= json_encode(
                    array_map(function ($i) use ($pdo) {
                            $stmt = $pdo->prepare("SELECT SUM(total) AS total FROM invoices WHERE DATE(created_at)=?");
                            $stmt->execute([date("Y-m-d", strtotime("-$i day"))]);
                            return (float) $stmt->fetch()['total'];
                        }, range(6, 0))
                ) ?>,
                fill: true,
                borderColor: 'rgb(13,110,253)',
                backgroundColor: 'rgba(13,110,253,0.3)',
                tension: 0.3
            }]
        }
    });
</script>