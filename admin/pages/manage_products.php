<?php
$parts = $pdo->query("
    SELECT p.*, c.name AS category
    FROM parts p
    LEFT JOIN categories c ON c.id = p.category_id
    WHERE p.is_active = 1
    ORDER BY p.name ASC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- SweetAlert2 CDN -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
/* -----------------------------------------------------
   BUTTON SIZE — MEDIUM (recommended)
------------------------------------------------------*/
.inv-btn-md {
  padding: 0.45rem 1rem !important;
  font-size: 0.9rem !important;
  border-radius: 50px !important;
  font-weight: 600 !important;
}

/* Add Product button */
.btn-accent-system {
  background: var(--color-primary) !important;
  color: white !important;
  border: none;
}
.btn-accent-system:hover {
  background: var(--color-primary) !important;
}

/* Stock In button */
.btn-primary-system {
  background: var(--color-primary) !important;
  color: var(--color-primary-content) !important;
  border: none;
}
.btn-primary-system:hover {
  background: var(--color-primary) !important;
}

/* Stock Out button WITH WHITE TEXT */
.btn-danger-system {
  background: var(--color-error) !important;
  color: white !important;
  border: none;
}
.btn-danger-system:hover {
  opacity: 0.9;
}

/* -----------------------------------------------------
   ACTION ICONS
------------------------------------------------------*/
.action-buttons {
  text-align: right !important; /* Move action icons to the far right */
}

.action-buttons i {
  cursor: pointer;
  margin: 0 6px;
  transition: 0.18s ease;
  font-size: 1.18rem;
  vertical-align: middle;
}

.action-buttons i:hover {
  transform: scale(1.18);
}

/* PLUS and MINUS use SECONDARY color */
.icon-plus {
  color: var(--color-secondary) !important;
}
.icon-minus {
  color: var(--color-secondary) !important;
}

/* EDIT icon also secondary */
.icon-edit {
  color: var(--color-secondary) !important;
}

/* DELETE icon left unchanged (danger) */

/* -----------------------------------------------------
   TABLE
------------------------------------------------------*/
.table thead th {
  background: var(--color-primary);
  color: var(--color-primary-content);
  border: none;
}
.table tbody tr:hover {
  background: var(--color-base-300);
}

/* SEARCH BOX */
.inv-search {
  width: 280px;
  padding: 0.45rem 0.9rem;
  border: 1px solid var(--color-base-300);
  border-radius: 50px;
  font-size: 0.9rem;
}
.inv-search:focus {
  outline: none;
  border-color: var(--color-primary);
}
</style>

<!-- TOP TOOLBAR -->
<div class="d-flex justify-content-between mb-3 align-items-center">

  <input type="text"
         id="searchBox"
         class="inv-search"
         placeholder="Search product...">

  <div class="d-flex gap-2">

    <!-- ADD PRODUCT -->
    <button class="inv-btn-md btn-accent-system"
            onclick="openAddProduct()">
      <i class="bi bi-plus-circle me-1"></i>
      Add Product
    </button>

    <!-- STOCK IN -->
    <button class="inv-btn-md btn-primary-system"
            onclick="openStockIn()">
      <i class="bi bi-arrow-down-circle me-1"></i>
      Stock In
    </button>

    <!-- STOCK OUT -->
    <button class="inv-btn-md btn-danger-system"
            onclick="openStockOut()">
      <i class="bi bi-arrow-up-circle me-1"></i>
      Stock Out
    </button>

  </div>
</div>

<!-- TABLE -->
<div class="table-responsive">
<table class="table table-hover align-middle" id="productsTable">
  <thead>
    <tr>
      <th>Product</th>
      <th>SKU</th>
      <th>Category</th>
      <th>Stock</th>
      <th style="width: 160px; text-align:right;">Actions</th>
    </tr>
  </thead>

  <tbody>
    <?php foreach ($parts as $p): ?>
    <tr id="row-product-<?= (int)$p['id'] ?>">
      <td><?= htmlspecialchars($p['name']) ?></td>
      <td><?= htmlspecialchars($p['sku']) ?></td>
      <td><?= htmlspecialchars($p['category'] ?? 'N/A') ?></td>
      <td id="stock-value-<?= (int)$p['id'] ?>"><?= (int)$p['stock'] ?></td>

      <td class="action-buttons">

        <!-- STOCK IN (+) -->
        <i title="Stock In"
           class="bi bi-plus-circle-fill icon-plus"
           onclick="openStockInProduct(<?= (int)$p['id'] ?>)"></i>

        <!-- STOCK OUT (−) -->
        <i title="Stock Out"
           class="bi bi-dash-circle-fill icon-minus"
           onclick="openStockOutProduct(<?= (int)$p['id'] ?>)"></i>

        <!-- EDIT -->
        <i title="Edit product"
           class="bi bi-pencil-square icon-edit"
           onclick="openEditModal(<?= (int)$p['id'] ?>)"></i>

        <!-- DELETE -->
        <i title="Delete product"
           class="bi bi-trash text-danger"
           onclick="promptDeleteProduct(<?= (int)$p['id'] ?>)"></i>

      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>

</table>
</div>

<script>
/* GLOBAL - pending delete id */
let pendingDeleteProductID = null;

/* LIVE SEARCH */
document.getElementById("searchBox").addEventListener("keyup", function() {
    const query = this.value.toLowerCase();
    const rows = document.querySelectorAll("#productsTable tbody tr");

    rows.forEach(row => {
        row.style.display = row.innerText.toLowerCase().includes(query)
            ? ""
            : "none";
    });
});

/* OPEN MODALS (no product preselected) */
function openAddProduct() {
  new bootstrap.Modal(document.getElementById("addProductModal")).show();
}

function openStockIn() {
  // clear preselect
  const sel = document.querySelector('#stockInModal select[name="part_id"]');
  if (sel) sel.value = "";
  // clear hidden id
  const hid = document.getElementById('stockIn_product_id');
  if (hid) hid.value = "";
  new bootstrap.Modal(document.getElementById("stockInModal")).show();
}

function openStockOut() {
  const sel = document.querySelector('#stockOutModal select[name="part_id"]');
  if (sel) sel.value = "";
  const hid = document.getElementById('stockOut_product_id');
  if (hid) hid.value = "";
  new bootstrap.Modal(document.getElementById("stockOutModal")).show();
}

/* OPEN STOCK IN FOR A SPECIFIC PRODUCT */
function openStockInProduct(id) {
  // set hidden id
  const hid = document.getElementById('stockIn_product_id');
  if (hid) hid.value = id;

  // set select to the product if exists
  const select = document.querySelector('#stockInModal select[name="part_id"]');
  if (select) {
    select.value = id;
    // try trigger change if needed
    select.dispatchEvent(new Event('change'));
  }

  new bootstrap.Modal(document.getElementById("stockInModal")).show();
}

/* OPEN STOCK OUT FOR A SPECIFIC PRODUCT */
function openStockOutProduct(id) {
  const hid = document.getElementById('stockOut_product_id');
  if (hid) hid.value = id;

  const select = document.querySelector('#stockOutModal select[name="part_id"]');
  if (select) {
    select.value = id;
    select.dispatchEvent(new Event('change'));
  }

  new bootstrap.Modal(document.getElementById("stockOutModal")).show();
}

/* EDIT PRODUCT
   The edit modal file defines openEditModal(id) that fetches details and shows modal.
   Ensure you include modals/edit_product.php at the bottom of this page (it's done).
*/

/* ------------------------------
   DELETE FLOW using Admin Verify
   ------------------------------ */
function promptDeleteProduct(id) {
  pendingDeleteProductID = id;
  // open admin verify modal
  const modal = new bootstrap.Modal(document.getElementById('globalAdminModal'));
  // clear password field and alerts
  const pw = document.getElementById('global_admin_password_input');
  const alertBox = document.getElementById('global_admin_alert');
  if (pw) pw.value = '';
  if (alertBox) { alertBox.innerHTML = ''; }
  modal.show();
}

/* Handle submission of admin verification (global modal) */
document.getElementById('globalAdminForm').addEventListener('submit', async function(e){
  e.preventDefault();
  const pwdInput = document.getElementById('global_admin_password_input');
  const alertBox = document.getElementById('global_admin_alert');

  if (!pwdInput) return;
  const password = pwdInput.value.trim();
  if (!password) {
    alertBox.innerHTML = '<div class="alert alert-danger">Password is required.</div>';
    return;
  }

  // call verify endpoint
  try {
    const res = await fetch('functions/verify_admin_password.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ password })
    });
    const j = await res.json();

    if (!j.success) {
      alertBox.innerHTML = '<div class="alert alert-danger">' + (j.message || 'Invalid password') + '</div>';
      return;
    }

    // Verified -> ask for sweetalert confirmation
    const modalEl = document.getElementById('globalAdminModal');
    bootstrap.Modal.getInstance(modalEl).hide();

    const confirm = await Swal.fire({
      title: 'Confirm delete',
      text: 'Are you sure you want to delete this product? This action cannot be undone.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, delete',
      cancelButtonText: 'Cancel',
      confirmButtonColor: '#d33'
    });

    if (!confirm.isConfirmed) {
      return;
    }

    // proceed to delete
    if (!pendingDeleteProductID) {
      Swal.fire('Error', 'No product selected.', 'error');
      return;
    }

    // call delete endpoint
    const delRes = await fetch('modals/delete_product.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ id: pendingDeleteProductID })
    });
    const delJson = await delRes.json();

    if (delJson.status === 'success') {
      await Swal.fire('Deleted', delJson.message || 'Product removed', 'success');
      // remove row visually before reload (optional)
      const row = document.getElementById('row-product-' + pendingDeleteProductID);
      if (row) row.remove();
      // small delay then reload to ensure consistency
      setTimeout(()=> location.reload(), 700);
    } else {
      Swal.fire('Error', delJson.message || 'Could not delete', 'error');
    }

  } catch (err) {
    console.error(err);
    alertBox.innerHTML = '<div class="alert alert-danger">Server error or network issue.</div>';
  }
});
</script>

