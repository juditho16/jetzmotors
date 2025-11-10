<?php
require_once "../config/db.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $service_id = $_POST['service_id'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $remarks = trim($_POST['remarks'] ?? '');

    $stmt = $pdo->prepare("
        INSERT INTO appointments (user_id, service_id, date, time, remarks, status)
        VALUES (?, ?, ?, ?, ?, 'pending')
    ");
    $stmt->execute([$user_id, $service_id, $date, $time, $remarks]);

    $_SESSION['booking_success'] = true;
    header("Location: ../user/index.php?page=appointments");
    exit;
}
