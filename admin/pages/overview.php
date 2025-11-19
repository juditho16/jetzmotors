<?php
// Fetch parts + categories + compute totals
$parts = $pdo->query("
    SELECT p.*, c.name AS category
    FROM parts p
    LEFT JOIN categories c ON c.id = p.category_id
    WHERE p.is_active = 1
    ORDER BY p.name ASC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
/* Filters */
.filters {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 1rem;
  flex-wrap: wrap;
}

.search-box {
  position: relative;
  max-width: 300px;
  flex: 1;
}

.search-box input {
  padding-left: 35px;
  border-radius: 50px !important;
}

.search-box i {
  position: absolute;
  left: 12px;
  top: 10px;
  color: #777;
}

/* Table */
.table thead th {
  background: var(--color-primary);
  color: var(--color-primary-content);
  border: none;
}

.table tbody tr:hover {
  background: var(--color-base-300);
}
</style>

<!-- FILTERS -->
<div class="filters">
  <div class="search-box">
    <i class="bi bi-search"></i>
    <input id="overviewSearch" class="form-control" placeholder="Search product...">
  </div>

  <select id="overviewCategory" class="form-select" style="max-width:200px;">
    <option value="">All Categories</option>
    <option value="Accessories">Accessories</option>
    <option value="Spare Parts">Spare Parts</option>
    <option value="Lubricants">Lubricants</option>
    <option value="Electrical">Electrical</option>
    <option value="Tires">Tires</option>
  </select>
</div>

<!-- TABLE -->
<div class="table-responsive mt-3">
<table class="table table-hover align-middle">
  <thead>
    <tr>
      <th>Product</th>
      <th>SKU</th>
      <th>Category</th>
      <th>Stock</th>
      <th>Unit Price</th>
      <th>Total Value</th>
    </tr>
  </thead>

  <tbody id="overviewTable">
    <?php foreach ($parts as $p): ?>
    <tr>
      <td><?= htmlspecialchars($p['name']) ?></td>
      <td><?= htmlspecialchars($p['sku']) ?></td>
      <td><?= htmlspecialchars($p['category'] ?? 'N/A') ?></td>
      <td><?= (int)$p['stock'] ?></td>
      <td>₱<?= number_format($p['unit_price'],2) ?></td>
      <td>₱<?= number_format($p['unit_price'] * $p['stock'],2) ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
</div>

<script>
document.getElementById("overviewSearch").addEventListener("input", function() {
  const q = this.value.toLowerCase();
  document.querySelectorAll("#overviewTable tr").forEach(row => {
    row.style.display = row.innerText.toLowerCase().includes(q) ? "" : "none";
  });
});

document.getElementById("overviewCategory").addEventListener("change", function() {
  const filter = this.value.toLowerCase();
  document.querySelectorAll("#overviewTable tr").forEach(row => {
    const category = row.children[2].innerText.toLowerCase();
    row.style.display = !filter || category.includes(filter) ? "" : "none";
  });
});
</script>
