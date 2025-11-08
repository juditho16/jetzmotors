<?php
require_once "../../config/db.php";
require_once "../../lib/functions.php";

$code = $_GET['code'] ?? '';
if (!$code) json_err("No SKU/Barcode provided");

// Search by SKU or Barcode
$stmt = $pdo->prepare("SELECT * FROM parts WHERE sku=? OR barcode=? LIMIT 1");
$stmt->execute([$code, $code]);
$product = $stmt->fetch();

if ($product) {
  json_ok(["product" => $product]);
} else {
  json_err("Product not found");
}
