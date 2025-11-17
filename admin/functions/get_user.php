<?php
// admin/functions/get_user.php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . "/../../config/db.php";

$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

if (!$user_id) {
    echo json_encode(['success'=>false, 'message'=>'Missing user_id']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT id, first_name, middle_initial, last_name, suffix, name, email, phone, address FROM users WHERE id = ? LIMIT 1");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode(['success'=>false, 'message'=>'User not found']);
        exit;
    }

    echo json_encode(['success'=>true, 'user'=>$user]);
    exit;

} catch (Exception $e) {
    echo json_encode(['success'=>false, 'message'=>'Server error: '.$e->getMessage()]);
    exit;
}
