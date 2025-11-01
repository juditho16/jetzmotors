<?php
// /admin/functions/checkout.php

require_once __DIR__ . "/../../config/db.php";
require_once __DIR__ . "/function.php";  // this has processCheckout()

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'checkout') {
    header('Content-Type: application/json');

    $userId = 1; // TODO: replace with logged-in cashier/admin
    $isMember = ($_POST['is_member'] ?? '0') === '1';
    $memberId = $_POST['member_id'] ?? null;
    $cart = json_decode($_POST['cart'], true);

    $result = processCheckout($pdo, $userId, $cart, $isMember, $memberId);

    echo json_encode($result);
    exit;
}
