<?php
if (!isset($appointments)) {
    require_once "../config/db.php";

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
}
?>

<style>
.table-card {
  background-color: var(--color-base-200);
  padding: 1rem;
  border-radius: var(--radius-box);
  box-shadow: 0 4px 10px rgba(0,0,0,0.12);
}

table thead th {
  background-color: var(--color-primary) !important;
  color: var(--color-primary-content) !important;
  border: none;
}

table tbody tr:hover {
  background-color: var(--color-base-300);
  transition: 0.2s;
}

.status-badge {
  padding: 6px 12px;
  border-radius: 50px;
  font-weight: 600;
  font-size: 0.8rem;
}

.status-pending { background: var(--color-warning); color: #000; }
.status-confirmed { background: var(--color-info); color: #fff; }
.status-completed { background: var(--color-success); color: #fff; }
.status-cancelled { background: var(--color-error); color: #fff; }
</style>

<div class="table-card mt-3">
  <h5 class="fw-bold mb-3"><i class="bi bi-list-ul me-2"></i>Appointment Records</h5>

  <div class="table-responsive">
    <table class="table table-hover text-center align-middle">
      <thead>
        <tr>
          <th>Ref No.</th>
          <th>Customer</th>
          <th>Vehicle</th>
          <th>Service</th>
          <th>Date</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>

      <tbody>
      <?php foreach ($appointments as $apt): ?>
        <?php
            $css = "status-" . strtolower($apt["status"]);
        ?>
        <tr>
          <td><?= htmlspecialchars($apt["ref_no"]) ?></td>
          <td><?= htmlspecialchars($apt["customer"]) ?></td>
          <td><?= htmlspecialchars($apt["vehicle"]) ?></td>
          <td><?= htmlspecialchars($apt["service"]) ?></td>
          <td><?= date("M d, Y", strtotime($apt["date"])) ?></td>
          <td><span class="<?= $css ?> status-badge"><?= ucfirst($apt["status"]) ?></span></td>
          <td>
            <button class="btn btn-outline-info btn-sm">
              <i class="bi bi-eye"></i>
            </button>
            <button class="btn btn-outline-success btn-sm">
              <i class="bi bi-check-circle"></i>
            </button>
            <button class="btn btn-outline-danger btn-sm">
              <i class="bi bi-x-circle"></i>
            </button>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
