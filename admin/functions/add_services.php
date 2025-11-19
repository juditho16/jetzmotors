<?php
require_once "../config/db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = $_POST["name"];
    $description = $_POST["description"];
    $price = $_POST["base_price"];

    $stmt = $pdo->prepare("
        INSERT INTO services (name, description, base_price)
        VALUES (?, ?, ?)
    ");
    $stmt->execute([$name, $description, $price]);

    header("Location: ../admin/manage_services.php?success=1");
    exit;
}
