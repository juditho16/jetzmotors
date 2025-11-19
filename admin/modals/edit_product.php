<?php
// /admin/modals/edit_product.php

require_once __DIR__ . "/../../config/db.php";
require_once __DIR__ . "/../../lib/functions.php";
/* JSON header for AJAX */
if ($_SERVER["REQUEST_METHOD"] === "POST" || isset($_GET["action"])) {
    header("Content-Type: application/json; charset=utf-8");
}

/* ============================================
   1) GET PRODUCT DETAILS
============================================ */
if (isset($_GET['action']) && $_GET['action'] === 'get_product') {
    $id = intval($_GET['id'] ?? 0);

    $stmt = $pdo->prepare("
        SELECT p.*, c.name AS category_name
        FROM parts p
        LEFT JOIN categories c ON c.id = p.category_id
        WHERE p.id = ?
        LIMIT 1
    ");
    $stmt->execute([$id]);
    echo json_encode($stmt->fetch(PDO::FETCH_ASSOC) ?: []);
    exit;
}

/* ============================================
   2) UPDATE PRODUCT (NO MORE ACTIVE TOGGLE)
============================================ */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'update_product') {

    $id          = intval($_POST['id']);
    $sku         = trim($_POST['sku']);
    $barcode     = trim($_POST['barcode']);
    $name        = trim($_POST['name']);
    $unit_price  = floatval($_POST['unit_price']);
    $stock       = intval($_POST['stock']);
    $category_id = !empty($_POST['category_id']) ? intval($_POST['category_id']) : null;
    $brand_id    = !empty($_POST['brand_id']) ? intval($_POST['brand_id']) : null;
    $model_id    = !empty($_POST['model_id']) ? intval($_POST['model_id']) : null;

    if ($id <= 0) {
        echo json_encode(['status'=>'error','message'=>'Invalid product.']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("
            UPDATE parts SET
                sku = ?,
                barcode = ?,
                name = ?,
                unit_price = ?,
                stock = ?,
                category_id = ?,
                brand_id = ?,
                model_id = ?
            WHERE id = ?
        ");

        $stmt->execute([
            $sku, $barcode, $name, $unit_price, $stock,
            $category_id, $brand_id, $model_id, $id
        ]);

        echo json_encode(['status'=>'success','message'=>'Product updated successfully.']);
        exit;

    } catch (Exception $e) {
        echo json_encode(['status'=>'error','message'=>'DB Error: '.$e->getMessage()]);
        exit;
    }
}

/* ============================================
   3) RENDER HTML MODAL
============================================ */

$categories = $pdo->query("SELECT id,name FROM categories ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
$brands     = $pdo->query("SELECT id,name FROM brands ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
$models     = $pdo->query("SELECT id,brand_id,name FROM models ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- EDIT PRODUCT MODAL -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">

      <form id="editProductForm">
        <input type="hidden" name="id" id="edit_id">

        <!-- HEADER WITH THEMING -->
        <div class="modal-header" style="background:var(--color-primary); color:var(--color-primary-content);">
          <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit Product</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter: brightness(0) invert(1);"></button>
        </div>

        <div class="modal-body">
          <div id="editAlert" class="alert d-none"></div>

          <div class="row g-2">

            <!-- PRODUCT NAME FIRST -->
            <div class="col-12">
              <label class="form-label">Product Name</label>
              <input type="text" id="edit_name" name="name" class="form-control">
            </div>

            <div class="col-md-6">
              <label class="form-label">SKU</label>
              <input type="text" id="edit_sku" name="sku" class="form-control">
            </div>

            <div class="col-md-6">
              <label class="form-label">Barcode</label>
              <input type="text" id="edit_barcode" name="barcode" class="form-control">
            </div>

            <div class="col-md-4">
              <label class="form-label">Category</label>
              <select id="edit_category" name="category_id" class="form-select">
                <option value="">-- Select --</option>
                <?php foreach ($categories as $c): ?>
                  <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="col-md-4">
              <label class="form-label">Brand</label>
              <select id="edit_brand" name="brand_id" class="form-select">
                <option value="">-- Select --</option>
                <?php foreach ($brands as $b): ?>
                  <option value="<?= $b['id'] ?>"><?= htmlspecialchars($b['name']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="col-md-4">
              <label class="form-label">Model</label>
              <select id="edit_model" name="model_id" class="form-select">
                <option value="">-- Select --</option>
                <?php foreach ($models as $m): ?>
                  <option data-brand="<?= $m['brand_id'] ?>" value="<?= $m['id'] ?>"><?= htmlspecialchars($m['name']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label">Unit Price</label>
              <input type="number" step="0.01" id="edit_price" name="unit_price" class="form-control">
            </div>

            <div class="col-md-6">
              <label class="form-label">Stock</label>
              <input type="number" id="edit_stock" name="stock" class="form-control">
            </div>

          </div><!-- row -->
        </div><!-- body -->

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-md rounded-pill" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary btn-md rounded-pill">
            <i class="bi bi-save me-1"></i>Save Changes
          </button>
        </div>

      </form>

    </div>
  </div>
</div>

<script>
function openEditModal(id) {
    fetch("modals/edit_product.php?action=get_product&id=" + id)
        .then(res => res.json())
        .then(data => {

            document.getElementById("edit_id").value = data.id;
            document.getElementById("edit_name").value = data.name;
            document.getElementById("edit_sku").value = data.sku;
            document.getElementById("edit_barcode").value = data.barcode;
            document.getElementById("edit_category").value = data.category_id;
            document.getElementById("edit_brand").value = data.brand_id;
            document.getElementById("edit_model").value = data.model_id;
            document.getElementById("edit_price").value = data.unit_price;
            document.getElementById("edit_stock").value = data.stock;

            // Filter model list by brand
            const brand = data.brand_id;
            document.querySelectorAll("#edit_model option").forEach(opt => {
                if (!opt.value) return;
                opt.style.display = (opt.dataset.brand == brand) ? "" : "none";
            });

            new bootstrap.Modal(document.getElementById("editProductModal")).show();
        });
}

document.getElementById("editProductForm").addEventListener("submit", function(e) {
    e.preventDefault();

    const alertBox = document.getElementById("editAlert");
    alertBox.classList.add("d-none");

    let formData = new FormData(this);
    formData.append("action","update_product");

    fetch("modals/edit_product.php", {
        method:"POST",
        body:formData
    })
    .then(r => r.json())
    .then(res => {
        if (res.status === "success") {
            alertBox.className = "alert alert-success";
            alertBox.innerText = res.message;
            alertBox.classList.remove("d-none");
            setTimeout(() => location.reload(), 600);
        } else {
            alertBox.className = "alert alert-danger";
            alertBox.innerText = res.message;
            alertBox.classList.remove("d-none");
        }
    });
});
</script>
