<?php
require_once "../config/db.php";
require_once "../lib/functions.php";

// Fetch summary data for cards
$totalProducts = $pdo->query("SELECT COUNT(*) FROM parts WHERE is_active = 1")->fetchColumn();
$lowStock      = $pdo->query("SELECT COUNT(*) FROM parts WHERE stock > 0 AND stock <= 5 AND is_active = 1")->fetchColumn();
$outOfStock    = $pdo->query("SELECT COUNT(*) FROM parts WHERE stock = 0 AND is_active = 1")->fetchColumn();
$totalValue    = $pdo->query("SELECT SUM(stock * unit_price) FROM parts WHERE is_active = 1")->fetchColumn() ?? 0;

$stocksIn  = $pdo->query("SELECT SUM(quantity) FROM stock_logs WHERE type='IN'")->fetchColumn() ?? 0;
$stocksOut = $pdo->query("SELECT SUM(quantity) FROM stock_logs WHERE type='OUT'")->fetchColumn() ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Inventory | Admin Panel</title>

<link rel="stylesheet" href="../../assets/css/app.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>

.page-header {
  display:flex; justify-content:space-between; align-items:center;
  margin-bottom:1.2rem;
}

.tab-btn {
  background:none; border:none; font-weight:600;
  padding:.45rem .9rem; cursor:pointer;
  border-bottom:3px solid transparent;
  transition:.2s;
  color:var(--color-secondary);
}
.tab-btn.active {
  color:var(--color-primary);
  border-bottom:3px solid var(--color-primary);
}

/* Summary Cards */
.summary-cards {
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
  gap:1rem;
  margin-bottom:1.5rem;
}

.summary-card {
  background:var(--color-base-200);
  border-radius:var(--radius-box);
  padding:1.2rem;
  display:flex;
  gap:1rem;
  align-items:center;
  box-shadow:0 4px 12px rgba(0,0,0,0.08);
  transition:.2s;
}
.summary-card:hover { transform:translateY(-3px); }

.summary-icon {
  font-size:2rem; color:var(--color-primary);
}

.summary-content h6 { margin:0; color:var(--color-secondary); font-weight:500; }
.summary-content h3 { margin:0; font-weight:700; }

</style>
</head>

<body>

<div class="container-fluid py-4">

  <!-- HEADER -->
  <div class="page-header">
    <h3><i class="bi bi-box-seam me-2"></i> Inventory</h3>
  </div>

  <!-- SUMMARY CARDS (DO NOT SEPARATE AS FILE) -->
  <div class="summary-cards">
    <div class="summary-card">
      <i class="bi bi-tags summary-icon"></i>
      <div class="summary-content">
        <h6>Total Products</h6>
        <h3><?= number_format($totalProducts) ?></h3>
      </div>
    </div>

    <div class="summary-card">
      <i class="bi bi-exclamation-triangle summary-icon"></i>
      <div class="summary-content">
        <h6>Low Stock</h6>
        <h3><?= number_format($lowStock) ?></h3>
      </div>
    </div>

    <div class="summary-card">
      <i class="bi bi-x-octagon summary-icon"></i>
      <div class="summary-content">
        <h6>Out of Stock</h6>
        <h3><?= number_format($outOfStock) ?></h3>
      </div>
    </div>

    <div class="summary-card">
      <i class="bi bi-cash-coin summary-icon"></i>
      <div class="summary-content">
        <h6>Total Inventory Value</h6>
        <h3>₱<?= number_format($totalValue,2) ?></h3>
      </div>
    </div>

    <div class="summary-card">
      <i class="bi bi-arrow-down-square summary-icon"></i>
      <div class="summary-content">
        <h6>Stocks In</h6>
        <h3><?= number_format($stocksIn) ?></h3>
      </div>
    </div>

    <div class="summary-card">
      <i class="bi bi-arrow-up-square summary-icon"></i>
      <div class="summary-content">
        <h6>Stocks Out</h6>
        <h3><?= number_format($stocksOut) ?></h3>
      </div>
    </div>
  </div>

  <!-- MEMBERSHIP-LIKE TAB BUTTONS -->
  <div class="d-flex gap-2 mb-3">
    <button id="tabOverview" class="tab-btn active">Overview</button>
    <button id="tabManage" class="tab-btn">Manage Products</button>
  </div>

  <!-- TAB CONTENTS -->
  <div id="overviewTab">
    <?php include "overview.php"; ?>
  </div>

  <div id="manageTab" style="display:none;">
    <?php include "manage_products.php"; ?>
  </div>




</div>

<?php
include "modals/add_product.php";
include "modals/stock_in.php";
include "modals/stock_out.php";
include "modals/edit_product.php";
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
/* TAB SWITCHING LIKE MEMBERSHIPS PAGE */
const tabOverview = document.getElementById("tabOverview");
const tabManage  = document.getElementById("tabManage");

const overviewTab = document.getElementById("overviewTab");
const manageTab   = document.getElementById("manageTab");

tabOverview.onclick = () => {
  tabOverview.classList.add("active");
  tabManage.classList.remove("active");

  overviewTab.style.display = "block";
  manageTab.style.display = "none";
};

tabManage.onclick = () => {
  tabManage.classList.add("active");
  tabOverview.classList.remove("active");

  overviewTab.style.display = "none";
  manageTab.style.display = "block";
};
</script>

</body>
</html>
