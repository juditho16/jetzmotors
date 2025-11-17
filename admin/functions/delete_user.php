<?php
// admin/functions/delete_user.php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . "/../../config/db.php";

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);

$user_id = isset($data['user_id']) ? intval($data['user_id']) : 0;

if (!$user_id) {
    echo json_encode(['success'=>false, 'message'=>'Missing user ID.']);
    exit;
}

try {

    // Begin transaction
    $pdo->beginTransaction();

    // Delete memberships
    $del = $pdo->prepare("DELETE FROM memberships WHERE user_id = ?");
    $del->execute([$user_id]);

    // Delete appointments
    $del = $pdo->prepare("DELETE FROM appointments WHERE user_id = ?");
    $del->execute([$user_id]);

    // Delete sales
    $del = $pdo->prepare("DELETE FROM sales WHERE user_id = ?");
    $del->execute([$user_id]);

    // Delete invoices + items
    $invoices = $pdo->prepare("SELECT id FROM invoices WHERE user_id = ?");
    $invoices->execute([$user_id]);
    $invRows = $invoices->fetchAll(PDO::FETCH_COLUMN);

    if (!empty($invRows)) {
        $inClause = implode(',', array_map('intval', $invRows));
        $pdo->exec("DELETE FROM invoice_items WHERE invoice_id IN ($inClause)");
        $pdo->exec("DELETE FROM invoices WHERE id IN ($inClause)");
    }

    // Delete vehicles
    $del = $pdo->prepare("DELETE FROM vehicles WHERE user_id = ?");
    $del->execute([$user_id]);

    // Delete reset tokens
    $del = $pdo->prepare("DELETE FROM reset_tokens WHERE user_id = ?");
    $del->execute([$user_id]);

    // Finally delete user
    $del = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $del->execute([$user_id]);

    $pdo->commit();

    echo json_encode(['success'=>true,'message'=>'User and all related data deleted successfully.']);
    exit;

} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    echo json_encode(['success'=>false,'message'=>'Server error: '.$e->getMessage()]);
    exit;
}
?>
