<?php
$current = $_GET['page'] ?? 'profile';
function active($slug, $current)
{
    return $slug === $current ? 'active' : '';
}
?>
<div class="bottom-nav d-md-none"> <!-- hides on md+ -->
    <a href="index.php?page=profile" class="<?= active('profile', $current) ?>">
        <i class="bi bi-person"></i> Profile
    </a>
    <a href="index.php?page=history" class="<?= active('history', $current) ?>">
        <i class="bi bi-clock-history"></i> History
    </a>
    <a href="index.php?page=appointments" class="<?= active('appointments', $current) ?>">
        <i class="bi bi-calendar-event"></i> Book
    </a>
    <a href="index.php?page=purchases" class="<?= active('purchases', $current) ?>">
        <i class="bi bi-receipt"></i> Purchases
    </a>
</div>