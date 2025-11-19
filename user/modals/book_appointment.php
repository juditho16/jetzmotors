<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once "../config/db.php";

if (empty($_SESSION['user_id'])) { exit("Unauthorized"); }

$user_id = $_SESSION['user_id'];

/* Fetch Services */
$serviceStmt = $pdo->query("SELECT id, name FROM services ORDER BY name ASC");
$services = $serviceStmt->fetchAll();

/* Fetch Vehicle Brands */
$brandStmt = $pdo->query("SELECT id, name FROM vehicle_brands ORDER BY name ASC");
$vehicleBrands = $brandStmt->fetchAll();
?>

<div class="modal fade" id="bookModal" tabindex="-1" aria-labelledby="bookModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content themed-modal" style="padding: 0px;">
      <div class="modal-header themed-modal-header">
        <h5 class="modal-title" id="bookModalLabel">
          <i class="bi bi-calendar-plus me-2"></i> Book Service Appointment
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body themed-modal-body">
        <form method="POST" action="../functions/book_appointment.php">

          <!-- SERVICE -->
          <div class="mb-3 text-start">
            <label class="form-label fw-semibold">Service</label>
            <select name="service_id" id="serviceSelect" class="form-select" required>
              <option value="">Select Service</option>

              <?php foreach ($services as $s): ?>
                <option value="<?= $s['id'] ?>">
                  <?= htmlspecialchars($s['name']) ?>
                </option>
              <?php endforeach; ?>

              <!-- ADDED -->
              <option value="0">Others</option>
            </select>
          </div>

          <!-- VEHICLE DETAILS -->
          <div class="row g-2">
            <div class="col-md-4 text-start">
              <label class="form-label fw-semibold">Vehicle Brand</label>
              <select id="brandSelect" name="brand_id" class="form-select" required>
                <option value="">Select Brand</option>
                <?php foreach ($vehicleBrands as $b): ?>
                  <option value="<?= $b['id'] ?>">
                    <?= htmlspecialchars($b['name']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="col-md-5 text-start">
              <label class="form-label fw-semibold">Vehicle Model</label>
              <select id="modelSelect" name="model_id" class="form-select" required>
                <option value="">Select Model</option>
              </select>
            </div>

            <div class="col-md-3 text-start">
              <label class="form-label fw-semibold">Year</label>
              <input type="number" id="vehicleYear" name="year" class="form-control" placeholder="2022" required>
            </div>
          </div>

          <!-- APPOINTMENT DATE -->
          <div class="mb-3 text-start mt-3">
            <label class="form-label fw-semibold">Select Date</label>
            <input id="appointmentDate" name="date" class="form-control" required>
          </div>

          <!-- REMARKS ADDED -->
          <div class="mb-3 text-start">
            <label class="form-label fw-semibold">Remarks</label>
            <textarea name="remarks" rows="3" class="form-control" placeholder="Additional notes or specific concerns..."></textarea>
          </div>

          <input type="hidden" name="user_id" value="<?= $user_id ?>">

          <div class="text-end">
            <button class="btn btn-primary">
              <i class="bi bi-calendar-check me-1"></i> Confirm Booking
            </button>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>

<script>
// BRAND → MODEL dynamic loader
document.getElementById("brandSelect").addEventListener("change", function() {
    const brandId = this.value;
    const modelSelect = document.getElementById("modelSelect");

    modelSelect.innerHTML = "<option>Loading...</option>";

    if (!brandId) {
        modelSelect.innerHTML = "<option value=''>Select Model</option>";
        return;
    }

    fetch(`../functions/get_vehicle_models.php?brand_id=${brandId}`)
        .then(res => res.json())
        .then(data => {
            modelSelect.innerHTML = "<option value=''>Select Model</option>";
            data.forEach(m => {
                modelSelect.innerHTML += `<option value="${m.id}">${m.name}</option>`;
            });
        });
});

// DATE PICKER
flatpickr("#appointmentDate", {
  minDate: "today",
  dateFormat: "Y-m-d",
});
</script>

<style>
.modal-dialog { display:flex; align-items:center; min-height:100vh; }
.themed-modal { background:var(--color-base-100); border-radius:var(--radius-box); }
.themed-modal-header { background:var(--color-primary); color:#fff; }
</style>
