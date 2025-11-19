<?php
// /admin/pages/pos.php

require_once __DIR__ . "/../../config/db.php";
require_once __DIR__ . "/../functions/posfunction.php";

// ✅ Handle checkout request (AJAX)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'checkout') {
    header('Content-Type: application/json');

    $userId = 1; // TODO: Replace with logged-in cashier/admin
    $isMember = ($_POST['is_member'] ?? '0') === '1';
    $memberId = $_POST['member_id'] ?? null; // membership_id (card number)
    $cart = json_decode($_POST['cart'], true);

    $result = processCheckout($pdo, $userId, $cart, $isMember, $memberId);

    echo json_encode($result);
    exit;
}

// ✅ Load products
$order = $_GET['sort'] ?? 'asc';
$search = $_GET['search'] ?? '';

$query = "SELECT * FROM parts WHERE is_active=1";
$params = [];

if ($search) {
    $query .= " AND (name LIKE ? OR sku LIKE ? OR id=?)";
    $params = ["%$search%", "%$search%", $search];
}

$query .= " ORDER BY unit_price " . ($order === 'desc' ? 'DESC' : 'ASC');

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$products = $stmt->fetchAll();

// ✅ Load memberships + users
$membersStmt = $pdo->query("
    SELECT m.membership_id, u.name, m.end_date
    FROM memberships m
    INNER JOIN users u ON m.user_id = u.id
    WHERE m.status = 'Active'
");
$members = $membersStmt->fetchAll();
?>

<style>
    /* Make cards smaller: 4 per row */
    .product-card {
        font-size: 0.78rem;
        padding: 8px;
    }
    .product-card .card-title {
        font-size: 0.85rem;
        font-weight: 600;
    }
    .product-card p {
        font-size: 0.75rem;
        margin-bottom: 4px;
    }

    /* Small Add button */
    .btn-add {
        font-size: 0.75rem !important;
        padding: 4px 8px !important;
        height: 26px !important;
        line-height: 16px;
    }

    /* 4 cards per row on large screens */
    @media (min-width: 992px) {
        .col-4-card {
            width: 25%;
        }
    }

    /* Improve search + sort height */
    .form-control {
        height: 38px !important;
        font-size: 0.9rem !important;
    }
    .form-select {
        height: 38px !important;
        font-size: 0.9rem !important;
    }
    .btn-search {
        height: 38px !important;
    }

    .btn-delete {
    width: 24px !important;
    height: 24px !important;
    padding: 0 !important;
    border-radius: 50% !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    font-size: 0.75rem !important;
    background-color: #dc3545 !important;
    color: #ffffff !important;
    border: none !important;
}


    /* Slim checkout + select-member buttons */
    #checkoutBtn,
    #selectMemberBtn,
    .select-member {
        height: 32px !important;
        font-size: 0.85rem !important;
        padding: 5px !important;
    }

    /* Cart table smaller font */
    #cartTable td,
    #cartTable th {
        font-size: 0.78rem !important;
    }
</style>


<div class="container-fluid">
    <h1 class="mb-3"><i class="bi bi-cart4 me-2"></i> Point of Sale</h1>

    <div class="row">
        <!-- Left: Products -->
        <div class="col-md-8">

            <form method="GET" action="index.php" class="row mb-2 g-2 align-items-center">
                <input type="hidden" name="page" value="pos">

                <div class="col-md-8">
                    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" 
                           class="form-control"
                           placeholder="Search by name, SKU, or ID">
                </div>

                <div class="col-md-3">
                    <select name="sort" class="form-select" onchange="this.form.submit()">
                        <option value="asc" <?= $order === 'asc' ? 'selected' : '' ?>>Price ↑</option>
                        <option value="desc" <?= $order === 'desc' ? 'selected' : '' ?>>Price ↓</option>
                    </select>
                </div>

                <div class="col-md-1">
                    <button class="btn btn-primary w-100 btn-search"><i class="bi bi-search"></i></button>
                </div>
            </form>

            <div class="row g-2">
                <?php foreach ($products as $p): ?>
                    <div class="col-4-card col-sm-6 col-md-4 col-lg-3">
                        <div class="card h-100 product-card">
                            <div class="card-body d-flex flex-column">
                                <h6 class="card-title"><?= htmlspecialchars($p['name']) ?></h6>
                                <p class="text-muted">SKU: <?= htmlspecialchars($p['sku']) ?></p>
                                <p class="fw-bold">₱<?= number_format($p['unit_price'], 2) ?></p>

                                <button class="btn btn-primary btn-add mt-auto add-to-cart"
                                    data-id="<?= $p['id'] ?>"
                                    data-name="<?= htmlspecialchars($p['name']) ?>"
                                    data-price="<?= $p['unit_price'] ?>">
                                    <i class="bi bi-plus-circle"></i> Add
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <?php if (!$products): ?>
                    <p class="text-muted">No products found.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Right: POS Cart -->
        <div class="col-md-4">
            <div class="card" style="height: calc(90vh - 70px);">
                <div class="card-header bg-dark text-white py-2">
                    <i class="bi bi-receipt me-2"></i> POS Counter
                </div>

                <div class="card-body d-flex flex-column">

                    <!-- Cart -->
                    <div class="table-responsive flex-grow-1" style="overflow-y:auto; max-height: 55vh;">
                        <table class="table table-sm align-middle" id="cartTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Item</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                    <hr class="my-2">

                    <!-- Select Member -->
                    <div class="mb-2">
                        <button id="selectMemberBtn" type="button" class="btn btn-outline-primary w-100"
                            data-bs-toggle="modal" data-bs-target="#memberModal">
                            <i class="bi bi-person-vcard me-2"></i> Select Member for Discount
                        </button>
                    </div>

                    <!-- Totals -->
                    <div id="totalsBox" style="font-size:0.85rem;">
                        <p class="mb-1 d-none" id="memberRow">
                            <strong>Member:</strong> <span id="memberName"></span>
                        </p>
                        <p class="mb-1">Merchandise Total: <span id="cartTotal">₱0.00</span></p>
                        <p class="mb-1 text-success d-none" id="discountRow">
                            <strong>Discount (3%):</strong> -<span id="cartDiscount">₱0.00</span>
                        </p>
                        <h5 style="font-size:1rem;">Total Payable: <span id="cartFinal">₱0.00</span></h5>
                    </div>

                    <button class="btn btn-success w-100 mt-2" id="checkoutBtn">
                        <i class="bi bi-cash-stack"></i> Checkout
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Member Modal -->
<div class="modal fade" id="memberModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header py-2">
                <h5 class="modal-title"><i class="bi bi-people me-2"></i> Select Member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <input type="text" id="memberSearch" class="form-control mb-2"
                    placeholder="Search by Membership ID or name">

                <table class="table table-sm table-hover">
                    <thead>
                        <tr>
                            <th>Membership ID</th>
                            <th>Name</th>
                            <th>Expiry</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="memberList">
                        <?php foreach ($members as $m): ?>
                            <tr>
                                <td><?= htmlspecialchars($m['membership_id']) ?></td>
                                <td><?= htmlspecialchars($m['name']) ?></td>
                                <td><?= htmlspecialchars($m['end_date']) ?></td>
                                <td>
                                    <button class="btn btn-success btn-sm select-member"
                                        data-id="<?= $m['membership_id'] ?>"
                                        data-name="<?= htmlspecialchars($m['name']) ?>">
                                        Select
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
let cart = [];
let selectedMember = null;

// Add to Cart
function addToCart(id, name, price) {
    const existing = cart.find(item => item.id == id);
    if (existing) {
        existing.qty++;
        existing.subtotal = existing.qty * existing.price;
    } else {
        cart.push({ id, name, price: parseFloat(price), qty: 1, subtotal: parseFloat(price) });
    }
    renderCart();
}

document.querySelectorAll('.add-to-cart').forEach(btn => {
    btn.addEventListener('click', () => {
        addToCart(btn.dataset.id, btn.dataset.name, btn.dataset.price);
    });
});

// Render Cart
function renderCart() {
    const tbody = document.querySelector('#cartTable tbody');
    tbody.innerHTML = '';
    let total = 0;

    cart.forEach((item, index) => {
        total += item.subtotal;
        tbody.innerHTML += `
            <tr>
                <td>${item.name}</td>
                <td><input type="number" min="1" value="${item.qty}"
                    class="form-control" style="width:55px"
                    onchange="updateQty(${index}, this.value)"></td>
                <td>₱${item.price.toFixed(2)}</td>
                <td>₱${item.subtotal.toFixed(2)}</td>
                <td><button class="btn-delete" onclick="removeItem(${index})">X</button> </td></tr>`;
    });

    const discountRate = selectedMember ? 0.03 : 0;
    const discount = total * discountRate;
    const final = total - discount;

    document.getElementById('cartTotal').textContent = '₱' + total.toFixed(2);
    document.getElementById('cartDiscount').textContent = '₱' + discount.toFixed(2);
    document.getElementById('cartFinal').textContent = '₱' + final.toFixed(2);

    document.getElementById('discountRow').classList.toggle('d-none', !selectedMember);

    if (selectedMember) {
        document.getElementById('memberRow').classList.remove('d-none');
        document.getElementById('memberName').textContent =
            `${selectedMember.name} (ID: ${selectedMember.id})`;
    } else {
        document.getElementById('memberRow').classList.add('d-none');
    }
}

function updateQty(index, qty) {
    qty = parseInt(qty);
    if (qty <= 0) qty = 1;
    cart[index].qty = qty;
    cart[index].subtotal = cart[index].price * qty;
    renderCart();
}

function removeItem(index) {
    cart.splice(index, 1);
    renderCart();
}

// Member Selection
document.querySelectorAll('.select-member').forEach(btn => {
    btn.addEventListener('click', () => {
        selectedMember = { id: btn.dataset.id, name: btn.dataset.name };
        renderCart();
        bootstrap.Modal.getInstance(document.getElementById('memberModal')).hide();
    });
});

// Member Search
document.getElementById('memberSearch').addEventListener('keyup', function () {
    const term = this.value.toLowerCase();
    document.querySelectorAll('#memberList tr').forEach(row => {
        row.style.display = row.innerText.toLowerCase().includes(term) ? '' : 'none';
    });
});

// FAILSAFE CLOSE FOR MODAL
document.querySelector('#memberModal .btn-close').addEventListener('click', function() {
    const modal = bootstrap.Modal.getInstance(document.getElementById('memberModal'));
    if (modal) modal.hide();
});

// Checkout
document.getElementById('checkoutBtn').addEventListener('click', () => {
    if (cart.length === 0) {
        Swal.fire('Oops!', 'Cart is empty!', 'warning');
        return;
    }

    const formData = new FormData();
    formData.append('action', 'checkout');
    formData.append('cart', JSON.stringify(cart));
    formData.append('is_member', selectedMember ? 1 : 0);
    if (selectedMember) formData.append('member_id', selectedMember.id);

    fetch("/jetzmotors/admin/functions/checkout.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                title: 'Checkout Successful',
                text: "Print receipt?",
                icon: 'success',
                showCancelButton: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.open("functions/receipt.php?invoice_id=" + data.invoice_id, "_blank");
                    location.reload();
                } else {
                    location.reload();
                }
            });
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    });
});
</script>
