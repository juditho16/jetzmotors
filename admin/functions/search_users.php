<?php
require_once "../config/db.php";
header('Content-Type: application/json');

$q = trim($_GET['q'] ?? '');
if ($q === '') {
  echo json_encode([]);
  exit;
}

$stmt = $pdo->prepare("SELECT id, name FROM users WHERE is_member=0 AND name LIKE ? ORDER BY name ASC LIMIT 10");
$stmt->execute(["%$q%"]);
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
?>
