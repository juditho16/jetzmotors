<?php
// admin/functions/create_new_member.php
header("Content-Type: application/json; charset=utf-8");
require_once "../../config/db.php";
require_once "../../lib/functions.php";

try {

    $required = [
        "first_name","last_name","email","phone","address",
        "password","password_verify",
        "start_date","end_date","discount_rate"
    ];

    foreach ($required as $r) {
        if (empty($_POST[$r])) {
            echo json_encode(["success"=>false, "message"=>"Missing: $r"]);
            exit;
        }
    }

    if ($_POST["password"] !== $_POST["password_verify"]) {
        echo json_encode(["success"=>false, "message"=>"Passwords do not match."]);
        exit;
    }

    // Create user
    $full_name = trim($_POST["first_name"]." ".$_POST["middle_initial"]." ".$_POST["last_name"]." ".$_POST["suffix"]);
    $hashed = password_hash($_POST["password"], PASSWORD_BCRYPT);

    $stmt = $pdo->prepare("
        INSERT INTO users (first_name,middle_initial,last_name,suffix,name,email,phone,address,password,is_member,member_until,created_at)
        VALUES (?,?,?,?,?,?,?,?,?,1,?,NOW())
    ");

    $stmt->execute([
        $_POST["first_name"],
        $_POST["middle_initial"],
        $_POST["last_name"],
        $_POST["suffix"],
        $full_name,
        $_POST["email"],
        $_POST["phone"],
        $_POST["address"],
        $hashed,
        $_POST["end_date"]
    ]);

    $user_id = $pdo->lastInsertId();

    // Membership ID
    $membership_id = "MEM-" . date("Y") . "-" . str_pad(rand(1,999),3,"0",STR_PAD_LEFT);

    // Insert membership
    $ins = $pdo->prepare("
        INSERT INTO memberships (user_id,membership_id,start_date,end_date,discount_rate,status,created_at)
        VALUES (?,?,?,?,?,'Active',NOW())
    ");

    $ins->execute([
        $user_id,
        $membership_id,
        $_POST["start_date"],
        $_POST["end_date"],
        $_POST["discount_rate"]
    ]);

    echo json_encode([
        "success"=>true,
        "message"=>"New member registered successfully."
    ]);
    exit;

} catch (Exception $e) {
    echo json_encode(["success"=>false, "message"=>$e->getMessage()]);
    exit;
}
