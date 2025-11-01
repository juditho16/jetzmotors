<?php
// Fetch all appointments with customer + service
$stmt = $pdo->query("
  SELECT a.*, u.name AS customer, s.name AS service
  FROM appointments a
  JOIN users u ON a.user_id=u.id
  JOIN services s ON a.service_id=s.id
  ORDER BY a.schedule_at DESC
");
$appointments = $stmt->fetchAll();
?>

<div class="container-fluid">
  <h1 class="mb-4"><i class="bi bi-calendar-event me-2"></i> Appointments</h1>

  <table class="table table-striped">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Customer</th>
        <th>Service</th>
        <th>Schedule</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($appointments as $a): ?>
      <tr>
        <td>#<?= $a['id'] ?></td>
        <td><?= sanitize($a['customer']) ?></td>
        <td><?= sanitize($a['service']) ?></td>
        <td><?= date("M d, Y h:i A", strtotime($a['schedule_at'])) ?></td>
        <td>
          <span class="badge bg-<?=
            $a['status']=="Pending" ? "warning" :
            ($a['status']=="In Progress" ? "info" :
            ($a['status']=="Completed" ? "success" : "danger"))
          ?>">
            <?= $a['status'] ?>
          </span>
        </td>
        <td>
          <div class="btn-group">
            <a href="../functions/update_status.php?id=<?= $a['id'] ?>&status=In Progress" class="btn btn-sm btn-info">Start</a>
            <a href="../functions/update_status.php?id=<?= $a['id'] ?>&status=Completed" class="btn btn-sm btn-success">Complete</a>
            <a href="../functions/update_status.php?id=<?= $a['id'] ?>&status=Cancelled" class="btn btn-sm btn-danger">Cancel</a>
          </div>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
