<?php
// functions.php

/**
 * Perform POS Checkout
 *
 * @param PDO   $pdo      Database connection
 * @param int   $userId   The user/customer ID (can be a walk-in or registered user)
 * @param array $cart     Array of cart items: [ [id, name, price, qty, subtotal], ... ]
 * @param bool  $isMember If true, apply membership discount
 *
 * @return array Result with success flag and transaction details
 */
function processCheckout(PDO $pdo, int $userId, array $cart, bool $isMember = false): array
{
    if (empty($cart)) {
        return ['success' => false, 'message' => 'Cart is empty.'];
    }

    try {
        $totalAmount = 0;
        foreach ($cart as $item) {
            $totalAmount += $item['price'] * $item['qty'];
        }

        $discountRate = $isMember ? 0.03 : 0;
        $discount = $totalAmount * $discountRate;
        $finalAmount = $totalAmount - $discount;

        // ✅ Begin transaction
        $pdo->beginTransaction();

        // Insert invoice
        $stmt = $pdo->prepare("INSERT INTO invoices (user_id, total, discount) VALUES (?, ?, ?)");
        $stmt->execute([$userId, $finalAmount, $discount]);
        $invoiceId = $pdo->lastInsertId();

        // Insert invoice items + update stock
        foreach ($cart as $item) {
            $partId = $item['id'];
            $qty = $item['qty'];
            $price = $item['price'];
            $subtotal = $price * $qty;

            // Insert invoice item
            $stmt = $pdo->prepare("INSERT INTO invoice_items (invoice_id, product_id, qty, price, subtotal) 
                                   VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$invoiceId, $partId, $qty, $price, $subtotal]);

            // Update stock
            $stmt = $pdo->prepare("UPDATE parts SET stock = stock - ? WHERE id = ?");
            $stmt->execute([$qty, $partId]);
        }

        // Insert into sales summary
        $stmt = $pdo->prepare("INSERT INTO sales (invoice_id, user_id, total_amount, discount_amount, final_amount) 
                               VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$invoiceId, $userId, $totalAmount, $discount, $finalAmount]);

        $pdo->commit();

        return [
            'success' => true,
            'invoice_id' => $invoiceId,
            'total' => $totalAmount,
            'discount' => $discount,
            'final' => $finalAmount
        ];

    } catch (Exception $e) {
        $pdo->rollBack();
        return ['success' => false, 'message' => $e->getMessage()];
    }
}
