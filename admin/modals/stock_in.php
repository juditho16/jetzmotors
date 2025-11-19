<?php
// /modals/stock_in.php
// Full modal + POST handler for performing Stock IN operation.
//
// MIGRATION NOTES: requires stock_logs table (see add_product.php comments)

require_once "../config/db.php";
require_once "../lib/functions.php";


/* FIX: Only send JSON header during AJAX calls */
if ($_SERVER["REQUEST_METHOD"] === "POST" || isset($_GET["action"])) {
    header("Content-Type: application/json; charset=utf-8");
}

// POST handler: stock in
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'stock_in') {
    $part_id = intval($_POST['part_id'] ?? 0);
    $qty = intval($_POST['quantity'] ?? 0);
    $remarks = trim($_POST['remarks'] ?? '');

    if ($part_id <= 0 || $qty <= 0) {
        echo json_encode(['status'=>'error','message'=>'Select product and enter a valid quantity.']);
        exit;
    }

    try {
        $pdo->beginTransaction();

        // update parts stock
        $stmt = $pdo->prepare("UPDATE parts SET stock = stock + ? WHERE id = ?");
        $stmt->execute([$qty, $part_id]);

        // insert log
        $stmt = $pdo->prepare("INSERT INTO stock_logs (part_id, `type`, quantity, remarks) VALUES (?, 'IN', ?, ?)");
        $stmt->execute([$part_id, $qty, $remarks]);

        $pdo->commit();
        echo json_encode(['status'=>'success','message'=>'Stock updated (IN).']);
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(['status'=>'error','message'=>'DB Error: '.$e->getMessage()]);
        exit;
    }
}

// Fetch parts for dropdown
$parts = $pdo->query("SELECT id, sku, name, stock FROM parts WHERE is_active = 1 ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Stock In Modal -->
<div class="modal fade" id="stockInModal" tabindex="-1" aria-labelledby="stockInLabel" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content">
      <form id="stockInForm">
        <div class="modal-header">
          <h5 class="modal-title" id="stockInLabel"><i class="bi bi-arrow-down-circle me-2"></i> Stock In</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <div id="stockInAlert" class="alert d-none" role="alert"></div>

          <div class="mb-2">
            <label class="form-label">Product</label>
            <select name="part_id" class="form-select" required>
              <option value="">-- Select product --</option>
              <?php foreach($parts as $p): ?>
                <option value="<?= (int)$p['id'] ?>"><?= htmlspecialchars($p['name']) ?> (<?= htmlspecialchars($p['sku']) ?>) — Stock: <?= (int)$p['stock'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-2">
            <label class="form-label">Quantity</label>
            <input type="number" name="quantity" class="form-control" min="1" value="1" required>
          </div>

          <div class="mb-2">
            <label class="form-label">Remarks</label>
            <textarea name="remarks" class="form-control" rows="2" placeholder="Optional"></textarea>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success rounded-pill"><i class="bi bi-save me-1"></i> Confirm Stock In</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
(function(){
  const form = document.getElementById('stockInForm');
  const alertBox = document.getElementById('stockInAlert');

  form.addEventListener('submit', async function(e){
    e.preventDefault();
    alertBox.classList.add('d-none');

    const data = new FormData(form);
    data.append('action','stock_in');

    try {
      const res = await fetch(location.pathname.replace('/modals/','/modals/') + 'stock_in.php', {
        method: 'POST',
        body: data
      });
      const json = await res.json();

      if (json.status === 'success') {
        alertBox.classList.remove('d-none','alert-danger');
        alertBox.classList.add('alert-success');
        alertBox.innerText = json.message;
        setTimeout(()=> {
          const modalEl = document.getElementById('stockInModal');
          const bsModal = bootstrap.Modal.getInstance(modalEl);
          bsModal.hide();
          location.reload();
        }, 600);
      } else {
        alertBox.classList.remove('d-none','alert-success');
        alertBox.classList.add('alert-danger');
        alertBox.innerText = json.message || 'Error';
      }
    } catch (err) {
      alertBox.classList.remove('d-none','alert-success');
      alertBox.classList.add('alert-danger');
      alertBox.innerText = 'Network error';
    }
  });
})();
</script>
