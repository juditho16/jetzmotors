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
  <!-- Header -->
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold"><i class="bi bi-calendar-event me-2"></i> My Appointments</h4>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#bookModal">
      <i class="bi bi-plus-circle me-1"></i> Book Appointment
    </button>
  </div>

  <!-- Appointments List -->
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

<!-- Modal: Book Appointment -->
<div class="modal fade" id="bookModal" tabindex="-1" aria-labelledby="bookModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content themed-modal" style ="padding: 0px;">
      <div class="modal-header themed-modal-header">
        <h5 class="modal-title" id="bookModalLabel"><i class="bi bi-calendar-plus me-2"></i> Book Service Appointment</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body themed-modal-body">
        <form method="POST" action="../functions/book_appointment.php">
          <!-- Service -->
          <div class="mb-3 text-start">
            <label class="form-label fw-semibold">Service</label>
            <select class="form-select" name="service_id" required>
              <option value="">Select Service</option>
              <option value="1">Oil Change</option>
              <option value="2">Brake Check</option>
              <option value="3">Full Service</option>
            </select>
          </div>

          <!-- Vehicle Row -->
          <div class="row g-2 align-items-end vehicle-row">
            <div class="col-md-4 text-start">
              <label class="form-label fw-semibold">Vehicle Brand</label>
              <select id="brandSelect" class="form-select" required>
                <option value="">Select Brand</option>
                <?php foreach ($brands as $b): ?>
                  <option value="<?= $b['id'] ?>"><?= htmlspecialchars($b['name']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="col-md-5 text-start">
              <label class="form-label fw-semibold">Vehicle Model</label>
              <select id="modelSelect" class="form-select" required>
                <option value="">Select Model</option>
              </select>
            </div>

            <div class="col-md-3 text-start">
              <label class="form-label fw-semibold">Year</label>
              <input type="number" class="form-control" name="year" placeholder="2022" required>
            </div>
          </div>

          <!-- Appointment Date -->
          <div class="mb-3 text-start mt-3">
            <label class="form-label fw-semibold">Select Date</label>
            <input id="appointmentDate" name="date" class="form-control" placeholder="Select a date" required>
          </div>

          <input type="hidden" name="user_id" value="<?= htmlspecialchars($user_id) ?>">

          <div class="text-end">
            <button type="submit" class="btn btn-primary">
              <i class="bi bi-calendar-check me-1"></i> Confirm Booking
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Custom Modal Styling -->
<style>
  /* Center modal properly and match system theme */
  .modal-dialog {
    margin: auto;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
  }

  .themed-modal {
    background-color: var(--color-base-100);
    color: var(--color-base-content);
    border-radius: var(--radius-box);
    border: none;
    box-shadow: 0 8px 25px rgba(0,0,0,0.3);
    padding: 0.8rem 1rem;
  }

  .themed-modal-header {
    background-color: var(--color-primary);
    color: var(--color-primary-content);
    border-top-left-radius: var(--radius-box);
    border-top-right-radius: var(--radius-box);
    border-bottom: none;
    padding: 0.8rem 1rem;
  }

  .themed-modal-body {
    background-color: var(--color-base-200);
    border-bottom-left-radius: var(--radius-box);
    border-bottom-right-radius: var(--radius-box);
    padding: 0.8rem 1rem;
  }

  .btn-close {
    filter: brightness(0) invert(1);
  }

  .card {
    padding: 0.6rem 1rem;
  }

  /* Mobile responsiveness */
  @media (max-width: 576px) {
    .modal-dialog {
      margin: 0 auto!important;
      padding: 0 1rem;
      align-items: center;
    }

    .themed-modal {
      max-width: 95%;
      padding: 0.6rem 0.8rem;
    }

    .themed-modal-header {
      padding: 0.6rem 0.9rem;
      text-align: center;
    }

    .themed-modal-body {
      padding: 0.6rem 0.9rem;
    }
  }
</style>

<!-- Flatpickr -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
  // Date Picker
  flatpickr("#appointmentDate", {
    minDate: "today",
    dateFormat: "Y-m-d",
    altInput: true,
    altFormat: "F j, Y",
    disable: [
      function(date) {
        return (date.getDay() === 0); // Disable Sundays
      }
    ]
  });

  // Brand → Model Fetch
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
