<?php
// admin/actions/create_member.php
session_start();
require_once "../../config/db.php";

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit;
}

// Collect data
$first_name       = trim($_POST['first_name'] ?? '');
$middle_initial   = trim($_POST['middle_initial'] ?? '');
$last_name        = trim($_POST['last_name'] ?? '');
$suffix           = trim($_POST['suffix'] ?? '');
$email            = trim($_POST['email'] ?? '');
$phone            = trim($_POST['phone'] ?? '');
$address          = trim($_POST['address'] ?? '');
$password         = trim($_POST['password'] ?? '');
$password_verify  = trim($_POST['password_verify'] ?? '');

$start_date       = trim($_POST['start_date'] ?? '');
$end_date         = trim($_POST['end_date'] ?? '');
$discount_rate    = trim($_POST['discount_rate'] ?? '');

$admin_password   = trim($_POST['admin_password'] ?? '');

// Build full formatted name
$full_name = trim("$first_name $middle_initial $last_name $suffix");

// Admin password verify
$admin = $pdo->prepare("SELECT * FROM admin LIMIT 1");
$admin->execute();
$adminRow = $admin->fetch(PDO::FETCH_ASSOC);

if (!$adminRow) {
    echo json_encode(['status' => 'error', 'message' => 'Admin account not found.']);
    exit;
}

if ($admin_password !== $adminRow['password']) {
    echo json_encode(['status' => 'error', 'message' => 'Admin password is incorrect.']);
    exit;
}

// Validate fields
if (!$first_name || !$last_name || !$email || !$phone || !$address || !$password) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
    exit;
}

if ($password !== $password_verify) {
    echo json_encode(['status' => 'error', 'message' => 'Passwords do not match.']);
    exit;
}

// Check duplicate email
$checkEmail = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$checkEmail->execute([$email]);

if ($checkEmail->rowCount() > 0) {
    echo json_encode(['status' => 'error', 'message' => 'Email already exists.']);
    exit;
}

// Insert user
try {
    $stmt = $pdo->prepare("
        INSERT INTO users 
        (first_name, middle_initial, last_name, suffix, name, email, phone, address, password, is_member, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1, NOW())
    ");

    $stmt->execute([
        $first_name,
        $middle_initial,
        $last_name,
        $suffix,
        $full_name,       // <-- FIX: Insert name properly
        $email,
        $phone,
        $address,
        $password         // unhashed (per your request)
    ]);

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'User insert failed: ' . $e->getMessage()]);
    exit;
}

$newUserId = $pdo->lastInsertId();

if (!$newUserId) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to get user ID.']);
    exit;
}

// Create membership
try {
    $membership_id = "MEM-" . date('Y') . "-" . str_pad(rand(1,999), 3, '0', STR_PAD_LEFT);

    $stmt = $pdo->prepare("
        INSERT INTO memberships (user_id, membership_id, start_date, end_date, discount_rate, status, created_at)
        VALUES (?, ?, ?, ?, ?, 'Active', NOW())
    ");

    $stmt->execute([
        $newUserId,
        $membership_id,
        date("Y-m-d", strtotime($start_date)),
        date("Y-m-d", strtotime($end_date)),
        $discount_rate
    ]);

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Membership insert failed: ' . $e->getMessage()]);
    exit;
}

echo json_encode([
    'status' => 'success',
    'message' => 'New member successfully created!',
    'user_id' => $newUserId,
    'full_name' => $full_name,
    'membership_id' => $membership_id
]);
exit;
