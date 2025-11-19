<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once "../config/db.php";

if (empty($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch brands
$brandStmt = $pdo->query("SELECT id, name FROM brands ORDER BY name ASC");
$brands = $brandStmt->fetchAll();

// Fetch user's vehicles
$vStmt = $pdo->prepare("
    SELECT v.*, b.name AS brand_name, m.name AS model_name
    FROM vehicles v
    JOIN brands b ON v.brand_id = b.id
    JOIN models m ON v.model_id = m.id
    WHERE v.user_id = ?
");
$vStmt->execute([$user_id]);
$vehicles = $vStmt->fetchAll();

// Fetch user's appointments
$aStmt = $pdo->prepare("
    SELECT a.*, s.name AS service_name, b.name AS brand_name, m.name AS model_name, v.year
    FROM appointments a
    JOIN vehicles v ON a.vehicle_id = v.id
    JOIN brands b ON v.brand_id = b.id
    JOIN models m ON v.model_id = m.id
    LEFT JOIN services s ON a.service_id = s.id
    WHERE a.user_id = ?
    ORDER BY a.date DESC
");
$aStmt->execute([$user_id]);
$appointments = $aStmt->fetchAll();
?>

<div class="container mt-3">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold"><i class="bi bi-calendar-event me-2"></i> My Appointments</h4>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#bookModal">
      <i class="bi bi-plus-circle me-1"></i> Book Appointment
    </button>
  </div>

  <?php if ($appointments): ?>
    <div class="row g-3">
      <?php foreach ($appointments as $appt): ?>
        <div class="col-12">
          <div class="card shadow-sm border-0">
            <div class="card-body d-flex justify-content-between align-items-center">
              <div>
                <h6 class="mb-1 fw-semibold"><?= htmlspecialchars($appt['service_name'] ?? 'Service') ?></h6>
                <p class="mb-0 text-muted small">
                  <?= htmlspecialchars($appt['brand_name'] . ' ' . $appt['model_name'] . ' (' . $appt['year'] . ')') ?><br>
                  <?= date("F d, Y", strtotime($appt['date'])) ?>
                </p>
              </div>
              <div class="text-end">
                <span class="badge bg-<?= strtolower($appt['status']) === 'confirmed' ? 'success' : (strtolower($appt['status']) === 'pending' ? 'warning' : 'secondary') ?>">
                  <?= ucfirst(htmlspecialchars($appt['status'])) ?>
                </span>
                <br>
                <a href="#" class="btn btn-outline-primary btn-sm mt-2">
                  <i class="bi bi-eye"></i> View
                </a>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <div class="alert alert-secondary text-center mt-4">
      <i class="bi bi-info-circle"></i> No appointments yet.
    </div>
  <?php endif; ?>
</div>

<?php include "modals/book_appointment.php"; ?>

<!-- Flatpickr -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
flatpickr("#appointmentDate", {
    minDate: "today",
    dateFormat: "Y-m-d",
    altInput: true,
    altFormat: "F j, Y",
    disable: [
      function(date) {
        return (date.getDay() === 0);
      }
    ]
});

document.getElementById('brandSelect').addEventListener('change', function () {
    const brandId = this.value;
    const modelSelect = document.getElementById('modelSelect');
    modelSelect.innerHTML = '<option>Loading...</option>';

    if (!brandId) {
      modelSelect.innerHTML = '<option value="">Select Model</option>';
      return;
    }

    fetch(`../functions/get_models.php?brand_id=${brandId}`)
      .then(res => res.json())
      .then(data => {
        modelSelect.innerHTML = '<option value="">Select Model</option>';
        data.forEach(model => {
          const opt = document.createElement('option');
          opt.value = model.id;
          opt.textContent = model.name;
          modelSelect.appendChild(opt);
        });
      })
      .catch(() => {
        modelSelect.innerHTML = '<option disabled>Error loading models</option>';
      });
});
</script>
