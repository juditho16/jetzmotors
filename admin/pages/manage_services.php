<?php
require_once "../config/db.php";

/* Fetch services */
$services = $pdo->query("
    SELECT id, name, description, base_price, created_at
    FROM services
    ORDER BY id DESC
")->fetchAll();

/* Fetch vehicle brands */
$brands = $pdo->query("
    SELECT id, name
    FROM vehicle_brands
    ORDER BY name ASC
")->fetchAll();

/* Fetch vehicle models */
$models = $pdo->query("
    SELECT vm.id, vm.name, vm.brand_id, vb.name AS brand_name
    FROM vehicle_models vm
    JOIN vehicle_brands vb ON vm.brand_id = vb.id
    ORDER BY vb.name ASC, vm.name ASC
")->fetchAll();
?>

<style>
.section-card {
  background-color: var(--color-base-200);
  padding: 1rem;
  border-radius: var(--radius-box);
  box-shadow: 0 4px 10px rgba(0,0,0,0.12);
}

/* Fix table hover */
.table-hover tbody tr:hover {
  background-color: var(--color-base-300);
}

/* Actions */
.action-icon {
  cursor: pointer;
  transition: .15s ease;
}
.action-icon:hover {
  transform: scale(1.2);
}

/* 60 / 40 Layout */
.grid-60-40 {
  display: grid;
  grid-template-columns: 60% 40%;
  gap: 1rem;
}

.brands-models-wrapper {
  display: grid;
  grid-template-rows: 1fr 1fr;
  gap: 1rem;
}

.sub-title {
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: .5rem;
}

/* Override Bootstrap buttons to use system theme colors */
.btn-theme {
  background-color: var(--color-primary) !important;
  color: var(--color-primary-content) !important;
  border-radius: 50px !important;
  border: none !important;
}

.btn-size {
  width: 200px;      /* change this to your desired width */
  height: 60px;      /* change this to your desired height */
  padding: 0 !important; /* prevents bootstrap padding override */
  display: flex;
  justify-content: center;
  align-items: center;
  border-radius: 50px; /* match your design if needed */
}

.btn-theme:hover {
  background-color: var(--color-secondary) !important;
}
</style>

<div class="grid-60-40 mt-3">

  <!-- ========================================================== -->
  <!-- LEFT SIDE (60%) — SERVICES TABLE -->
  <!-- ========================================================== -->
  <div class="section-card">

    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="fw-bold sub-title"><i class="bi bi-tools"></i> Services</h5>
      <button class="btn btn-theme btn-sm" data-bs-toggle="modal" data-bs-target="#addServiceModal">
        <i class="bi bi-plus-circle me-1"></i> Add Service
      </button>
    </div>

    <div class="table-responsive">
      <table class="table table-hover text-center align-middle">
        <thead>
          <tr>
            <th>ID</th>
            <th>Service Name</th>
            <th>Description</th>
            <th>Base Price</th>
            <th>Created</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($services as $s): ?>
          <tr>
            <td><?= $s["id"] ?></td>
            <td><?= htmlspecialchars($s["name"]) ?></td>
            <td><?= htmlspecialchars($s["description"]) ?></td>
            <td>₱<?= number_format($s["base_price"], 2) ?></td>
            <td><?= date("M d, Y", strtotime($s["created_at"])) ?></td>
            <td>
              <i class="bi bi-pencil-square text-info action-icon me-2"></i>
              <i class="bi bi-trash text-danger action-icon"></i>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>

  </div>

  <!-- ========================================================== -->
  <!-- RIGHT SIDE (40%) — STACKED BRANDS & MODELS TABLES -->
  <!-- ========================================================== -->
  <div class="brands-models-wrapper">

    <!-- VEHICLE BRANDS -->
    <div class="section-card">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h6 class="fw-bold sub-title"><i class="bi bi-grid-1x2"></i> Vehicle Brands</h6>
        <button class="btn btn-theme btn-sm" data-bs-toggle="modal" data-bs-target="#addBrandModal">
          <i class="bi bi-plus-circle me-1"></i> Add
        </button>
      </div>

      <div style="max-height: 240px; overflow-y:auto;">
        <table class="table table-hover text-center align-middle">
          <thead>
            <tr>
              <th>ID</th>
              <th>Brand</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($brands as $b): ?>
            <tr>
              <td><?= $b["id"] ?></td>
              <td><?= htmlspecialchars($b["name"]) ?></td>
              <td>
                <i class="bi bi-pencil-square text-info action-icon me-2"></i>
                <i class="bi bi-trash text-danger action-icon"></i>
              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- VEHICLE MODELS -->
    <div class="section-card">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h6 class="fw-bold sub-title"><i class="bi bi-diagram-3"></i> Vehicle Models</h6>
        <button class="btn btn-theme btn-sm" data-bs-toggle="modal" data-bs-target="#addModelModal">
          <i class="bi bi-plus-circle me-1"></i> Add
        </button>
      </div>

      <div style="max-height: 240px; overflow-y:auto;">
        <table class="table table-hover text-center align-middle">
          <thead>
            <tr>
              <th>ID</th>
              <th>Brand</th>
              <th>Model</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($models as $m): ?>
            <tr>
              <td><?= $m["id"] ?></td>
              <td><?= htmlspecialchars($m["brand_name"]) ?></td>
              <td><?= htmlspecialchars($m["name"]) ?></td>
              <td>
                <i class="bi bi-pencil-square text-info action-icon me-2"></i>
                <i class="bi bi-trash text-danger action-icon"></i>
              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

  </div>
</div>

<!-- ========================================================== -->
<!-- ADD SERVICE MODAL -->
<!-- ========================================================== -->
<div class="modal fade" id="addServiceModal">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="background:var(--color-base-200);">
      <div class="modal-header" style="background:var(--color-primary); color:var(--color-primary-content);">
        <h5 class="modal-title fw-bold">Add Service</h5>
        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <form action="../functions/add_service.php" method="POST">
        <div class="modal-body">
          <label class="form-label fw-semibold">Service Name</label>
          <input type="text" name="name" class="form-control mb-2" required>
          <label class="form-label fw-semibold">Description</label>
          <textarea name="description" class="form-control mb-2"></textarea>
          <label class="form-label fw-semibold">Base Price</label>
          <input type="number" name="base_price" class="form-control" step="0.01" required>
        </div>
        <div class="modal-footer">
          <button class="btn btn-theme btn-sm" type="submit">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ========================================================== -->
<!-- ADD BRAND MODAL -->
<!-- ========================================================== -->
<div class="modal fade" id="addBrandModal">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="background:var(--color-base-200);">
      <div class="modal-header" style="background:var(--color-primary); color:var(--color-primary-content);">
        <h5 class="modal-title fw-bold">Add Brand</h5>
        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <form action="../functions/add_brand.php" method="POST">
        <div class="modal-body">
          <label class="form-label fw-semibold">Brand Name</label>
          <input type="text" name="name" class="form-control" required>
        </div>
        <div class="modal-footer">
          <button class="btn btn-theme btn-sm" type="submit">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ========================================================== -->
<!-- ADD MODEL MODAL -->
<!-- ========================================================== -->
<div class="modal fade" id="addModelModal">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="background:var(--color-base-200);">
      <div class="modal-header" style="background:var(--color-primary); color:var(--color-primary-content);">
        <h5 class="modal-title fw-bold">Add Model</h5>
        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <form action="../functions/add_model.php" method="POST">
        <div class="modal-body">
          <label class="form-label fw-semibold">Select Brand</label>
          <select name="brand_id" class="form-select mb-2" required>
            <option value="">Choose Brand</option>
            <?php foreach ($brands as $b): ?>
              <option value="<?= $b["id"] ?>"><?= htmlspecialchars($b["name"]) ?></option>
            <?php endforeach; ?>
          </select>

          <label class="form-label fw-semibold">Model Name</label>
          <input type="text" name="name" class="form-control" required>
        </div>
        <div class="modal-footer">
          <button class="btn btn-theme btn-sm" type="submit">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>
