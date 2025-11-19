<?php
require_once "../config/db.php";
require_once "../lib/functions.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" || isset($_GET["action"])) {
    header("Content-Type: application/json; charset=utf-8");
}

/* ============================================
   ADD PRODUCT
============================================ */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'add_product') {

    $sku        = trim($_POST['sku'] ?? '');
    $barcode    = trim($_POST['barcode'] ?? '');
    $name       = trim($_POST['name'] ?? '');
    $unit_price = floatval($_POST['unit_price'] ?? 0);
    $stock      = intval($_POST['stock'] ?? 0);

    $category_id = !empty($_POST['category_id']) ? intval($_POST['category_id']) : null;
    $brand_id    = !empty($_POST['brand_id']) ? intval($_POST['brand_id']) : null;
    $model_id    = !empty($_POST['model_id']) ? intval($_POST['model_id']) : null;

    if ($sku === '' || $name === '') {
        echo json_encode(['status' => 'error', 'message' => 'SKU and Product name are required.']);
        exit;
    }

    try {
        // Check duplicate SKU
        $stmt = $pdo->prepare("SELECT id FROM parts WHERE sku = ? LIMIT 1");
        $stmt->execute([$sku]);
        if ($stmt->fetch()) {
            echo json_encode(['status' => 'error', 'message' => 'SKU already exists.']);
            exit;
        }

        // Prepare dynamic insert
        $cols = ['sku','barcode','name','unit_price','stock'];
        $placeholders = ['?','?','?','?','?'];
        $values = [$sku, $barcode, $name, $unit_price, $stock];

        if ($category_id !== null) { $cols[]='category_id'; $placeholders[]='?'; $values[]=$category_id; }
        if ($brand_id !== null)   { $cols[]='brand_id';   $placeholders[]='?'; $values[]=$brand_id; }
        if ($model_id !== null)   { $cols[]='model_id';   $placeholders[]='?'; $values[]=$model_id; }

        $sql = "INSERT INTO parts (" . implode(',', $cols) . ") VALUES (" . implode(',', $placeholders) . ")";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($values);

        echo json_encode(['status' => 'success', 'message' => 'Product added.']);
        exit;

    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'DB Error: '.$e->getMessage()]);
        exit;
    }
}

/* ============================================
   FETCH DROPDOWNS
============================================ */
$categories = $pdo->query("SELECT id, name FROM categories ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
$brands = $pdo->query("SELECT id, name FROM brands ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
$models = $pdo->query("SELECT id, brand_id, name FROM models ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

/* Auto-create default categories (if empty) */
if (empty($categories)) {
    $defaults = ['Accessories','Spare Parts','Lubricants','Batteries','Electrical','Tires','Motorcycle Parts'];
    $ins = $pdo->prepare("INSERT INTO categories (name) VALUES (?)");
    foreach ($defaults as $d) {
        $chk = $pdo->prepare("SELECT id FROM categories WHERE name = ? LIMIT 1");
        $chk->execute([$d]);
        if (!$chk->fetch()) $ins->execute([$d]);
    }
    $categories = $pdo->query("SELECT id, name FROM categories ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
}
?>

<style>
  #addProductModal .btn {
      padding: 6px 14px !important;
      font-size: 0.85rem !important;
  }
</style>

<!-- ADD PRODUCT MODAL -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">

      <form id="addProductForm">

        <!-- HEADER MATCHING EDIT PRODUCT -->
        <div class="modal-header" style="background:var(--color-primary); color:white;">
          <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i> Add Product</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div id="addProductAlert" class="alert d-none" role="alert"></div>

          <div class="row g-3">

            <!-- PRODUCT NAME -->
            <div class="col-12">
              <label class="form-label">Product Name <span class="text-danger">*</span></label>
              <input name="name" class="form-control" required>
            </div>

            <!-- SKU + BARCODE -->
            <div class="col-md-6">
              <label class="form-label">SKU <span class="text-danger">*</span></label>
              <input name="sku" class="form-control" required>
            </div>

            <div class="col-md-6">
              <label class="form-label">Barcode</label>
              <input name="barcode" class="form-control">
            </div>

            <!-- CATEGORY | BRAND | MODEL -->
            <div class="col-md-4">
              <label class="form-label">Category</label>
              <select name="category_id" id="add_category" class="form-select">
                <option value="">-- Select Category --</option>
                <?php foreach($categories as $c): ?>
                  <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="col-md-4">
              <label class="form-label">Brand</label>
              <select name="brand_id" id="add_brand" class="form-select">
                <option value="">-- Select Brand --</option>
                <?php foreach($brands as $b): ?>
                  <option value="<?= $b['id'] ?>"><?= htmlspecialchars($b['name']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="col-md-4">
              <label class="form-label">Model</label>
              <select name="model_id" id="add_model" class="form-select">
                <option value="">-- Select Model --</option>
                <?php foreach($models as $m): ?>
                  <option data-brand="<?= $m['brand_id'] ?>" value="<?= $m['id'] ?>">
                    <?= htmlspecialchars($m['name']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <!-- PRICE + STOCK -->
            <div class="col-md-6">
              <label class="form-label">Unit Price</label>
              <input type="number" step="0.01" name="unit_price" class="form-control" value="0.00">
            </div>

            <div class="col-md-6">
              <label class="form-label">Initial Stock</label>
              <input type="number" name="stock" class="form-control" value="0">
            </div>

          </div><!-- row -->
        </div><!-- body -->

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-add rounded-pill">
            <i class="bi bi-save me-1"></i> Save Product
          </button>
        </div>

      </form>

    </div>
  </div>
</div>

<script>
(function(){
  const form = document.getElementById('addProductForm');
  const alertBox = document.getElementById('addProductAlert');

  // Filter models by brand
  const brandSelect = document.getElementById('add_brand');
  const modelSelect = document.getElementById('add_model');
  brandSelect.addEventListener('change', function(){
    const b = this.value;
    Array.from(modelSelect.options).forEach(opt=>{
      if (!opt.value) return;
      const brand = opt.getAttribute('data-brand') || '';
      opt.style.display = (!b || brand === b) ? '' : 'none';
    });
    modelSelect.value = '';
  });

  form.addEventListener('submit', async function(e){
    e.preventDefault();
    alertBox.classList.add('d-none');

    const data = new FormData(form);
    data.append('action','add_product');

    try {
      const res = await fetch('add_product.php', { method:'POST', body:data });
      const json = await res.json();

      if (json.status === 'success') {
        alertBox.classList.remove('d-none','alert-danger');
        alertBox.classList.add('alert-success');
        alertBox.innerText = json.message;

        setTimeout(()=> {
          bootstrap.Modal.getInstance(document.getElementById('addProductModal')).hide();
          location.reload();
        }, 500);

      } else {
        alertBox.classList.remove('d-none','alert-success');
        alertBox.classList.add('alert-danger');
        alertBox.innerText = json.message;
      }
    } catch (err) {
      alertBox.classList.remove('d-none','alert-success');
      alertBox.classList.add('alert-danger');
      alertBox.innerText = 'Network error';
    }
  });
})();
</script>
