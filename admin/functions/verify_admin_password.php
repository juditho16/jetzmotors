<?php
// admin/functions/verify_admin_password.php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . "/../../config/db.php";
require_once __DIR__ . "/../../lib/functions.php";
session_start();

$input = json_decode(file_get_contents('php://input'), true);
$password = trim($input['password'] ?? '');

if ($password === '') {
  echo json_encode(['success' => false, 'message' => 'Password required.']);
  exit;
}

try {
  // Try to find the current admin id from session (common keys)
  $adminId = $_SESSION['admin_id'] ?? $_SESSION['user_id'] ?? null;
  $stmt = null;

  if ($adminId) {
    $stmt = $pdo->prepare("SELECT * FROM admin WHERE id = ? LIMIT 1");
    $stmt->execute([$adminId]);
  } else {
    // fallback: find first admin with username 'admin'
    $stmt = $pdo->prepare("SELECT * FROM admin ORDER BY id ASC LIMIT 1");
    $stmt->execute();
  }

  $admin = $stmt->fetch(PDO::FETCH_ASSOC);
  if (!$admin) {
    echo json_encode(['success' => false, 'message' => 'Admin account not found.']);
    exit;
  }

  $stored = $admin['password'] ?? '';

  // If password is empty in DB, reject.
  if ($stored === '') {
    echo json_encode(['success' => false, 'message' => 'Admin password is not set.']);
    exit;
  }

  // If stored looks like a password_hash (starts with $2y$ or $argon2 or $2a$) use password_verify
  $verified = false;
  if (password_needs_rehash($stored, PASSWORD_DEFAULT) || password_verify($password, $stored)) {
    // If password_verify returns true OR stored uses hash and we didn't error, treat accordingly.
    if (password_verify($password, $stored)) {
      $verified = true;
    } else {
      // If stored is not actually a hash, fall through to direct compare below
      $verified = false;
    }
  }

  // fallback: direct compare (legacy plain text)
  if (!$verified) {
    if (hash_equals($stored, $password)) {
      $verified = true;
    }
  }

  if ($verified) {
    echo json_encode(['success' => true, 'message' => 'Verified']);
    exit;
  } else {
    echo json_encode(['success' => false, 'message' => 'Invalid password.']);
    exit;
  }

} catch (Exception $ex) {
  error_log("verify_admin_password error: " . $ex->getMessage());
  echo json_encode(['success' => false, 'message' => 'Server error']);
  exit;
}
