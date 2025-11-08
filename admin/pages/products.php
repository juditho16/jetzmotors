<?php
// Search handling
$search = $_GET['search'] ?? '';
$params = [];

$query = "SELECT * FROM parts WHERE 1=1";
if ($search) {
  $query .= " AND (name LIKE ? OR sku LIKE ? OR barcode LIKE ?)";
  $params = ["%$search%", "%$search%", "%$search%"];
}
$query .= " ORDER BY name ASC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$products = $stmt->fetchAll();
?>

<div class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-box-seam me-2"></i> Products</h1>
    <!-- Add Product Button -->
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addProductModal">
      <i class="bi bi-plus-circle"></i> Add Product
    </button>
  </div>

  <!-- Search Bar -->
  <form method="GET" action="index.php" class="mb-3">
    <input type="hidden" name="page" value="products">
    <div class="input-group" style="max-width:400px;">
      <input type="text" name="search" value="<?= sanitize($search) ?>" class="form-control" placeholder="Search by name, SKU, or barcode">
      <button class="btn btn-primary"><i class="bi bi-search"></i></button>
    </div>
  </form>

  <!-- Product List -->
  <table class="table table-striped">
    <thead class="table-dark">
      <tr>
        <th>ID</th><th>SKU</th><th>Barcode</th><th>Name</th><th>Price</th><th>Stock</th><th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($products as $p): ?>
      <tr>
        <td><?= $p['id'] ?></td>
        <td><?= sanitize($p['sku']) ?></td>
        <td><?= sanitize($p['barcode'] ?? '-') ?></td>
        <td><?= sanitize($p['name']) ?></td>
        <td><?= fmt_money($p['unit_price']) ?></td>
        <td><?= $p['stock'] ?></td>
        <td>
          <a href="../functions/delete_product.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-danger">
            <i class="bi bi-trash"></i>
          </a>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php if (!$products): ?>
      <tr><td colspan="7" class="text-center text-muted">No products found.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form method="POST" action="../functions/add_product.php">
        <div class="modal-header bg-dark text-white">
          <h5 class="modal-title" id="addProductModalLabel"><i class="bi bi-plus-circle"></i> Add New Product</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-3">
              <label class="form-label">SKU</label>
              <input type="text" name="sku" class="form-control" placeholder="SKU code" required>
            </div>
            <div class="col-md-3">
              <label class="form-label">Barcode</label>
              <input type="text" name="barcode" class="form-control" placeholder="Barcode (optional)">
            </div>
            <div class="col-md-6">
              <label class="form-label">Product Name</label>
              <input type="text" name="name" class="form-control" placeholder="Product Name" required>
            </div>
            <div class="col-md-4">
              <label class="form-label">Price</label>
              <input type="number" step="0.01" name="unit_price" class="form-control" placeholder="₱0.00" required>
            </div>
            <div class="col-md-4">
              <label class="form-label">Stock</label>
              <input type="number" name="stock" class="form-control" placeholder="Stock qty" required>
            </div>
            <div class="col-md-4">
              <label class="form-label">Active</label>
              <select name="is_active" class="form-select">
                <option value="1" selected>Yes</option>
                <option value="0">No</option>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-success"><i class="bi bi-save"></i> Save Product</button>
        </div>
      </form>
    </div>
  </div>
</div>
