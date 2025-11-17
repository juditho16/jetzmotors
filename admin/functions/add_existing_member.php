<?php
// admin/functions/add_existing_member.php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . "/../../config/db.php";
require_once __DIR__ . "/../../lib/functions.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status'=>'error','message'=>'Invalid request method.']);
    exit;
}

$user_id        = intval($_POST['user_id'] ?? 0);
$start_date     = trim($_POST['start_date'] ?? '');
$end_date       = trim($_POST['end_date'] ?? '');
$discount_rate  = trim($_POST['discount_rate'] ?? '3.00');

// We NO longer require first_name, last_name, email, phone
// Global Admin modal does the verification now

// Validate only essential fields
if (!$user_id || !$start_date || !$end_date) {
    echo json_encode(['status'=>'error','message'=>'Missing required fields.']);
    exit;
}

try {

    // Ensure user exists
    $u = $pdo->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
    $u->execute([$user_id]);
    $user = $u->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode(['status'=>'error','message'=>'User not found.']);
        exit;
    }

    // Create membership ID
    $membership_id = "MEM-" . date('Y') . "-" . str_pad(rand(1,999), 3, '0', STR_PAD_LEFT);

    // Insert membership record
    $ins = $pdo->prepare("
        INSERT INTO memberships 
        (user_id, membership_id, start_date, end_date, discount_rate, status, created_at) 
        VALUES (?, ?, ?, ?, ?, 'Active', NOW())
    ");

    $ins->execute([
        $user_id,
        $membership_id,
        date("Y-m-d", strtotime($start_date)),
        date("Y-m-d", strtotime($end_date)),
        $discount_rate
    ]);

    // Update user membership flag
    $upd = $pdo->prepare("UPDATE users SET is_member = 1, member_until = ? WHERE id = ?");
    $upd->execute([
        date("Y-m-d", strtotime($end_date)),
        $user_id
    ]);

    echo json_encode([
        'status'=>'success',
        'message'=>'User converted to member successfully.',
        'membership_id'=>$membership_id
    ]);
    exit;

} catch (Exception $e) {
    echo json_encode([
        'status'=>'error',
        'message'=>'Server error: '.$e->getMessage()
    ]);
    exit;
}
