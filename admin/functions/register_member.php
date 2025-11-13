<?php
require_once "../config/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $discount_rate = $_POST['discount_rate'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $membership_id = "MEM-" . date('Y') . "-" . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);

    $stmt = $pdo->prepare("
        INSERT INTO memberships (user_id, membership_id, discount_rate, start_date, end_date, status)
        VALUES (?, ?, ?, ?, ?, 'Active')
    ");
    $stmt->execute([$user_id, $membership_id, $discount_rate, $start_date, $end_date]);

    // Update user table
    $pdo->prepare("UPDATE users SET is_member=1, member_until=? WHERE id=?")->execute([$end_date, $user_id]);

    header("Location: ../admin/memberships.php?success=1");
    exit;
}
?>
