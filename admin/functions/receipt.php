<?php
// /admin/functions/receipt.php

require_once __DIR__ . "/../../config/db.php";
require_once __DIR__ . "/../../vendor/autoload.php";  // Composer autoload for TCPDF

$invoiceId = $_GET['invoice_id'] ?? 0;

$stmt = $pdo->prepare("
    SELECT i.id, i.total, i.discount, i.created_at, u.name, m.membership_id
    FROM invoices i
    LEFT JOIN users u ON i.user_id = u.id
    LEFT JOIN memberships m ON m.user_id = u.id
    WHERE i.id = ?
");
$stmt->execute([$invoiceId]);
$invoice = $stmt->fetch();

if (!$invoice) {
    die("Invoice not found");
}

$stmt = $pdo->prepare("
    SELECT ii.qty, ii.price, p.name 
    FROM invoice_items ii
    INNER JOIN parts p ON ii.product_id = p.id
    WHERE ii.invoice_id = ?
");
$stmt->execute([$invoiceId]);
$items = $stmt->fetchAll();

$pdf = new TCPDF();
$pdf->AddPage();

// ✅ Use Unicode font so ₱ is displayed correctly
$pdf->SetFont('dejavusans', '', 12);

$html = "
<h2 style='text-align:center;'>JETZMOTORS</h2>
<h4 style='text-align:center;'>Official Receipt</h4>
<hr>
<p>
<b>Invoice #:</b> {$invoice['id']}<br>
<b>Date:</b> {$invoice['created_at']}<br>
<b>Customer:</b> {$invoice['name']}<br>" .
    ($invoice['membership_id'] ? "<b>Membership ID:</b> {$invoice['membership_id']}<br>" : "")
    . "</p>
<table border='1' cellpadding='4'>
<thead>
<tr>
    <th>Item</th>
    <th>Qty</th>
    <th>Price</th>
    <th>Subtotal</th>
</tr>
</thead>
<tbody>";

$total = 0;
foreach ($items as $it) {
    $subtotal = $it['qty'] * $it['price'];
    $total += $subtotal;
    $html .= "<tr>
        <td>{$it['name']}</td>
        <td>{$it['qty']}</td>
        <td>₱" . number_format($it['price'], 2) . "</td>
        <td>₱" . number_format($subtotal, 2) . "</td>
    </tr>";
}

$html .= "</tbody></table><br>
<table>
<tr><td><b>Total:</b></td><td>₱" . number_format($total, 2) . "</td></tr>
<tr><td><b>Discount:</b></td><td>₱" . number_format($invoice['discount'], 2) . "</td></tr>
<tr><td><b>Final Amount:</b></td><td>₱" . number_format($invoice['total'], 2) . "</td></tr>
</table>
<hr>
<p style='text-align:center;'>Thank you for your purchase!</p>
";

$pdf->writeHTML($html);
$pdf->Output("receipt-{$invoiceId}.pdf", "I");
