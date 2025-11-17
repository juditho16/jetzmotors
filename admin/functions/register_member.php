<?php
// admin/functions/register_member.php
header("Content-Type: application/json; charset=utf-8");
require_once __DIR__ . "/../../config/db.php";
require_once __DIR__ . "/../../lib/functions.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
    exit;
}

$user_id       = intval($_POST["user_id"] ?? 0);
$discount_rate = trim($_POST["discount_rate"] ?? "3.00");

if (!$user_id) {
    echo json_encode(["success" => false, "message" => "No user selected."]);
    exit;
}

// Confirm user exists
$u = $pdo->prepare("SELECT id FROM users WHERE id = ? LIMIT 1");
$u->execute([$user_id]);
if ($u->rowCount() === 0) {
    echo json_encode(["success" => false, "message" => "User does not exist."]);
    exit;
}

/* -----------------------------------------
   AUTO GENERATE MEMBERSHIP DATA
------------------------------------------*/
$start_date = date("Y-m-d");
$end_date   = date("Y-m-d", strtotime("+6 months"));

$membership_id = "MEM-" . date("Y") . "-" . str_pad(rand(1, 999), 3, "0", STR_PAD_LEFT);

try {
    $pdo->beginTransaction();

    // Update user record
    $update = $pdo->prepare("
        UPDATE users 
        SET is_member = 1, member_until = ? 
        WHERE id = ?
    ");
    $update->execute([$end_date, $user_id]);

    // Insert membership
    $ins = $pdo->prepare("
        INSERT INTO memberships (
            user_id, membership_id, discount_rate, start_date, end_date, status, created_at
        ) VALUES (?, ?, ?, ?, ?, 'Active', NOW())
    ");

    $ins->execute([
        $user_id,
        $membership_id,
        $discount_rate,
        $start_date,
        $end_date
    ]);

    $pdo->commit();

    echo json_encode([
        "success" => true,
        "message" => "Member registered successfully.",
        "membership_id" => $membership_id
    ]);

} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    echo json_encode([
        "success" => false,
        "message" => "Error: " . $e->getMessage()
    ]);
}
