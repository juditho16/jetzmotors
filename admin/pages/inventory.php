<?php
require_once "../config/db.php";
require_once "../lib/functions.php";

// Example counts (replace later with database queries)
$totalProducts = 128;
$lowStock = 7;
$outOfStock = 3;
$totalValue = 345800.75;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Inventory | Jetz Motors Admin</title>
  <link rel="stylesheet" href="../../assets/css/app.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
  .page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
  }

  .summary-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 1rem;
  }

  .summary-card {
    background: var(--color-base-200);
    border-radius: var(--radius-box);
    padding: 1.5rem;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    transition: transform 0.2s ease;
  }

  .summary-card:hover {
    transform: translateY(-3px);
  }

  .summary-card h6 {
    color: var(--color-secondary);
    font-weight: 500;
  }

  .summary-card h3 {
    margin-top: 0.4rem;
    font-weight: 700;
    font-family: var(--font-primary);
  }

  .filters {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin: 1.5rem 0;
  }

  .filters .search-box {
    position: relative;
    flex: 1;
    max-width: 300px;
  }

  .filters .search-box input {
    padding-left: 35px;
    border-radius: 50px;
  }

  .filters .search-box i {
    position: absolute;
    left: 12px;
    top: 9px;
    color: #888;
  }

  .filters select {
    border-radius: 50px;
    padding: 0.5rem 1rem;
  }

  table {
    border-radius: var(--radius-box);
    overflow: hidden;
  }

  th {
    background-color: var(--color-primary);
    color: var(--color-primary-content);
    font-weight: 500;
  }

  tbody tr:hover {
    background-color: var(--color-base-300);
  }

  .status-badge {
    border-radius: 30px;
    padding: 0.3rem 0.8rem;
    font-size: 0.85rem;
    font-weight: 600;
  }

  .status-instock {
    background: var(--color-success);
    color: #fff;
  }

  .status-lowstock {
    background: var(--color-warning);
    color: #000;
  }

  .status-outstock {
    background: var(--color-error);
    color: #fff;
  }

  .action-buttons i {
    cursor: pointer;
    margin: 0 6px;
    transition: 0.2s ease;
  }

  .action-buttons i:hover {
    transform: scale(1.2);
  }

  .btn-add {
    background: var(--color-accent);
    color: var(--color-base-content);
    border-radius: 50px;
    border: none;
  }

  .btn-add:hover {
    background: var(--color-primary);
    color: var(--color-primary-content);
  }
  </style>
</head>
<body>
<div class="container-fluid py-4">

  <div class="page-header">
    <h3><i class="bi bi-box-seam me-2"></i>Inventory</h3>
    <button class="btn btn-add">
      <i class="bi bi-plus-circle me-1"></i> Add Product
    </button>
  </div>

  <!-- Summary Cards -->
  <div class="summary-cards mb-4">
    <div class="summary-card">
      <h6>Total Products</h6>
      <h3><?= $totalProducts ?></h3>
    </div>
    <div class="summary-card">
      <h6>Low Stock</h6>
      <h3><?= $lowStock ?></h3>
    </div>
    <div class="summary-card">
      <h6>Out of Stock</h6>
      <h3><?= $outOfStock ?></h3>
    </div>
    <div class="summary-card">
      <h6>Total Inventory Value</h6>
      <h3>₱<?= number_format($totalValue, 2) ?></h3>
    </div>
  </div>

  <!-- Filters -->
  <div class="filters">
    <div class="search-box">
      <i class="bi bi-search"></i>
      <input type="text" id="searchInput" class="form-control" placeholder="Search product...">
    </div>
    <select id="categoryFilter" class="form-select w-auto">
      <option value="">All Categories</option>
      <option>Motorcycles</option>
      <option>Accessories</option>
      <option>Spare Parts</option>
    </select>
  </div>

  <!-- Inventory Table -->
  <div class="table-responsive">
    <table class="table table-hover align-middle">
      <thead>
        <tr>
          <th>#</th>
          <th>Product Name</th>
          <th>Category</th>
          <th>Brand</th>
          <th>Stock</th>
          <th>Price</th>
          <th>Status</th>
          <th class="text-center">Actions</th>
        </tr>
      </thead>
      <tbody id="inventoryTable">
        <tr>
          <td>1</td>
          <td>Yamaha Aerox</td>
          <td>Motorcycles</td>
          <td>Yamaha</td>
          <td>12</td>
          <td>₱125,000.00</td>
          <td><span class="status-badge status-instock">In Stock</span></td>
          <td class="text-center action-buttons">
            <i class="bi bi-eye text-info"></i>
            <i class="bi bi-pencil-square text-warning"></i>
            <i class="bi bi-trash text-danger"></i>
          </td>
        </tr>
        <tr>
          <td>2</td>
          <td>Motul 7100 Oil</td>
          <td>Accessories</td>
          <td>Motul</td>
          <td>3</td>
          <td>₱980.00</td>
          <td><span class="status-badge status-lowstock">Low Stock</span></td>
          <td class="text-center action-buttons">
            <i class="bi bi-eye text-info"></i>
            <i class="bi bi-pencil-square text-warning"></i>
            <i class="bi bi-trash text-danger"></i>
          </td>
        </tr>
        <tr>
          <td>3</td>
          <td>Honda Beat Tire</td>
          <td>Spare Parts</td>
          <td>Michelin</td>
          <td>0</td>
          <td>₱2,150.00</td>
          <td><span class="status-badge status-outstock">Out of Stock</span></td>
          <td class="text-center action-buttons">
            <i class="bi bi-eye text-info"></i>
            <i class="bi bi-pencil-square text-warning"></i>
            <i class="bi bi-trash text-danger"></i>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Live Search
document.getElementById("searchInput").addEventListener("input", function() {
  const query = this.value.toLowerCase();
  document.querySelectorAll("#inventoryTable tr").forEach(row => {
    const match = row.innerText.toLowerCase().includes(query);
    row.style.display = match ? "" : "none";
  });
});

// Category Filter
document.getElementById("categoryFilter").addEventListener("change", function() {
  const category = this.value.toLowerCase();
  document.querySelectorAll("#inventoryTable tr").forEach(row => {
    const catCell = row.children[2].innerText.toLowerCase();
    row.style.display = !category || catCell.includes(category) ? "" : "none";
  });
});
</script>
</body>
</html>
