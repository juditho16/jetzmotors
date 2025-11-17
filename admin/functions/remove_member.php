<?php
// admin/functions/remove_member.php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . "/../../config/db.php";
require_once __DIR__ . "/../../lib/functions.php";

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);

$user_id = isset($data['user_id']) ? intval($data['user_id']) : 0;

// ❌ admin_password removed — global modal handles it

if (!$user_id) {
    echo json_encode(['success'=>false, 'message'=>'Missing user ID.']);
    exit;
}

try {

    // Optional: fetch user
    $u = $pdo->prepare("SELECT id, name, email, phone, address FROM users WHERE id = ? LIMIT 1");
    $u->execute([$user_id]);
    $user = $u->fetch(PDO::FETCH_ASSOC);

    // Remove membership
    $del = $pdo->prepare("DELETE FROM memberships WHERE user_id = ?");
    $del->execute([$user_id]);

    // Reset user flags
    $upd = $pdo->prepare("UPDATE users SET is_member = 0, member_until = NULL WHERE id = ?");
    $upd->execute([$user_id]);

    echo json_encode([
        'success' => true,
        'message' => 'Membership removed.',
        'user_id' => $user_id,
        'name'    => $user['name'] ?? '',
        'email'   => $user['email'] ?? '',
        'phone'   => $user['phone'] ?? '',
        'address' => $user['address'] ?? ''
    ]);
    exit;

} catch (Exception $e) {

    echo json_encode([
        'success'=>false, 
        'message'=>'Server error: '.$e->getMessage()
    ]);
    exit;
}
