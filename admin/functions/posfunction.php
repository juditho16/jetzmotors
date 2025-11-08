<?php
function processCheckout($pdo, $userId, $cart, $isMember, $memberCardId = null)
{
    try {
        if (!$cart || count($cart) === 0) {
            return ['success' => false, 'message' => 'Cart is empty.'];
        }

        $totalAmount = 0;
        foreach ($cart as $item) {
            $totalAmount += $item['price'] * $item['qty'];
        }

        $discountRate = $isMember ? 0.03 : 0;
        $discount = $totalAmount * $discountRate;
        $finalAmount = $totalAmount - $discount;

        $pdo->beginTransaction();

        // ✅ Insert invoice
        $stmt = $pdo->prepare("INSERT INTO invoices (user_id, total, discount, membership_id) VALUES (?, ?, ?, ?)");
        $stmt->execute([$userId, $finalAmount, $discount, $memberCardId]);
        $invoiceId = $pdo->lastInsertId();

        // ✅ Insert invoice items + update stock
        foreach ($cart as $item) {
            $partId = $item['id'];
            $qty = $item['qty'];
            $price = $item['price'];
            $subtotal = $price * $qty;

            $stmt = $pdo->prepare("INSERT INTO invoice_items (invoice_id, product_id, qty, price, subtotal) 
                                   VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$invoiceId, $partId, $qty, $price, $subtotal]);

            $stmt = $pdo->prepare("UPDATE parts SET stock = stock - ? WHERE id = ?");
            $stmt->execute([$qty, $partId]);
        }

        // ✅ Insert into sales
        $stmt = $pdo->prepare("INSERT INTO sales (invoice_id, user_id, membership_id, total_amount, discount_amount, final_amount) 
                               VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$invoiceId, $userId, $memberCardId, $totalAmount, $discount, $finalAmount]);

        $pdo->commit();

        return [
            'success' => true,
            'invoice_id' => $invoiceId,
            'total' => $totalAmount,
            'discount' => $discount,
            'final' => $finalAmount,
            'member_id' => $memberCardId
        ];
    } catch (Exception $e) {
        $pdo->rollBack();
        return ['success' => false, 'message' => $e->getMessage()];
    }
}
