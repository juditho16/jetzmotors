<?php
require_once "../config/db.php";

/**
 * Register a new user into the database
 */
function registerUser($pdo, $data)
{
    try {
        // Collect and sanitize input
        $first_name = trim($data['first_name'] ?? '');
        $middle_initial = trim($data['middle_initial'] ?? '');
        $last_name = trim($data['last_name'] ?? '');
        $suffix = trim($data['suffix'] ?? '');
        $email = trim($data['email'] ?? '');
        $phone = trim($data['phone'] ?? '');
        $address = trim($data['address'] ?? '');
        $password = trim($data['password'] ?? '');

        // Validation
        if (empty($first_name) || empty($last_name) || empty($email) || empty($phone) || empty($address) || empty($password)) {
            return ['status' => 'error', 'message' => 'Please fill in all required fields.'];
        }

        // Check duplicates
        $check = $pdo->prepare("SELECT id FROM users WHERE email = ? OR phone = ? LIMIT 1");
        $check->execute([$email, $phone]);
        if ($check->fetch()) {
            return ['status' => 'error', 'message' => 'Email or phone number already registered.'];
        }

        // Use plain password (no hashing for debugging)
        $plainPassword = $password;
        $full_name = trim("$first_name $middle_initial $last_name $suffix");

        // Insert new user
        $stmt = $pdo->prepare("
            INSERT INTO users (first_name, middle_initial, last_name, suffix, name, email, phone, address, password)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $first_name,
            $middle_initial,
            $last_name,
            $suffix,
            $full_name,
            $email,
            $phone,
            $address,
            $plainPassword
        ]);

        return ['status' => 'success', 'message' => 'Registration successful! Redirecting...'];
    } catch (PDOException $e) {
        return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
    }
}
