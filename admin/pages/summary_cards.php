<?php
// summary_cards.php
require_once "../config/db.php";

// ===========================
//  TOTAL PRODUCTS
// ===========================
$totalProducts = $pdo->query("
    SELECT COUNT(*) AS total 
    FROM parts 
    WHERE is_active = 1
")->fetch()['total'];

// ===========================
//  LOW STOCK (≤ 5, > 0 )
// ===========================
$lowStock = $pdo->query("
    SELECT COUNT(*) AS total 
    FROM parts 
    WHERE stock > 0 AND stock <= 5 AND is_active = 1
")->fetch()['total'];

// ===========================
//  OUT OF STOCK (0)
// ===========================
$outOfStock = $pdo->query("
    SELECT COUNT(*) AS total 
    FROM parts 
    WHERE stock = 0 AND is_active = 1
")->fetch()['total'];

// ===========================
//  TOTAL INVENTORY VALUE
// ===========================
$totalValue = $pdo->query("
    SELECT SUM(stock * unit_price) AS total 
    FROM parts 
    WHERE is_active = 1
")->fetch()['total'] ?? 0;

// ===========================
//  STOCKS IN / OUT
// ===========================
$stocksIn = $pdo->query("
    SELECT SUM(quantity) AS total 
    FROM stock_logs 
    WHERE type = 'IN'
")->fetch()['total'] ?? 0;

$stocksOut = $pdo->query("
    SELECT SUM(quantity) AS total 
    FROM stock_logs 
    WHERE type = 'OUT'
")->fetch()['total'] ?? 0;
?>

<style>
.summary-cards {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1rem;
}

.summary-card {
  background: var(--color-base-200);
  border-radius: var(--radius-box);
  padding: 1.2rem;
  display: flex;
  align-items: center;
  gap: 1rem;
  box-shadow: 0 4px 12px rgba(0,0,0,0.08);
  transition: 0.2s ease;
}

.summary-card:hover {
  transform: translateY(-3px);
}

.summary-icon {
  font-size: 1.9rem;
  color: var(--color-primary);
}

.summary-content h6 {
  margin: 0;
  color: var(--color-secondary);
  font-weight: 500;
}

.summary-content h3 {
  margin: 0;
  font-weight: 700;
}
</style>

<div class="summary-cards mb-4">

  <!-- TOTAL PRODUCTS -->
  <div class="summary-card">
    <i class="bi bi-tags summary-icon"></i>
    <div class="summary-content">
      <h6>Total Products</h6>
      <h3><?= number_format($totalProducts) ?></h3>
    </div>
  </div>

  <!-- LOW STOCK -->
  <div class="summary-card">
    <i class="bi bi-exclamation-triangle summary-icon"></i>
    <div class="summary-content">
      <h6>Low Stock</h6>
      <h3><?= number_format($lowStock) ?></h3>
    </div>
  </div>

  <!-- OUT OF STOCK -->
  <div class="summary-card">
    <i class="bi bi-x-octagon summary-icon"></i>
    <div class="summary-content">
      <h6>Out of Stock</h6>
      <h3><?= number_format($outOfStock) ?></h3>
    </div>
  </div>

  <!-- INVENTORY VALUE -->
  <div class="summary-card">
    <i class="bi bi-cash-coin summary-icon"></i>
    <div class="summary-content">
      <h6>Total Inventory Value</h6>
      <h3>₱<?= number_format($totalValue, 2) ?></h3>
    </div>
  </div>

  <!-- STOCKS IN -->
  <div class="summary-card">
    <i class="bi bi-arrow-down-square summary-icon"></i>
    <div class="summary-content">
      <h6>Stocks In</h6>
      <h3><?= number_format($stocksIn) ?></h3>
    </div>
  </div>

  <!-- STOCKS OUT -->
  <div class="summary-card">
    <i class="bi bi-arrow-up-square summary-icon"></i>
    <div class="summary-content">
      <h6>Stocks Out</h6>
      <h3><?= number_format($stocksOut) ?></h3>
    </div>
  </div>

</div>
