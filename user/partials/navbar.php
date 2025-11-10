<?php
$current = $_GET['page'] ?? 'profile';
function active($slug, $current)
{
    return $slug === $current ? 'active' : '';
}
?>
<div class="bottom-nav d-md-none"> <!-- Mobile only -->
    <a href="index.php?page=profile" class="<?= active('profile', $current) ?>">
        <i class="bi <?= $current === 'profile' ? 'bi-person-fill' : 'bi-person' ?>"></i>
    </a>
    <a href="index.php?page=history" class="<?= active('history', $current) ?>">
        <i class="bi <?= $current === 'history' ? 'bi-clock-history' : 'bi-clock' ?>"></i>
    </a>
    <a href="index.php?page=appointments" class="<?= active('appointments', $current) ?>">
        <i class="bi <?= $current === 'appointments' ? 'bi-calendar-event-fill' : 'bi-calendar-event' ?>"></i>
    </a>
    <a href="index.php?page=purchase" class="<?= active('purchases', $current) ?>">
        <i class="bi <?= $current === 'purchases' ? 'bi-receipt' : 'bi-receipt-cutoff' ?>"></i>
    </a>
</div>

<style>
/* =========================================
   📱 MOBILE BOTTOM NAV - FILLED ICONS VERSION
   ========================================= */
.bottom-nav {
  position: fixed;
  bottom: 0;
  left: 0;
  width: 100%;
  background: var(--color-base-100);
  display: flex;
  justify-content: space-around;
  align-items: center;
  padding: 0.7rem 0;
  box-shadow: 0 -3px 10px rgba(0, 0, 0, 0.08);
  z-index: 999;
  transition: background-color 0.3s ease;
}

[data-theme="dark"] .bottom-nav {
  background: var(--color-primary);
}

/* Links (icons only) */
.bottom-nav a {
  flex: 1;
  text-align: center;
  text-decoration: none;
  color: var(--color-primary);
  transition: color 0.3s ease, transform 0.2s ease;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

[data-theme="dark"] .bottom-nav a {
  color: var(--color-primary-content);
}

/* Icon styles */
.bottom-nav i {
  font-size: 1.9rem;
  display: block;
  transition: all 0.25s ease;
}

/* Active (filled) icon highlight */
.bottom-nav a.active i {
  color: var(--color-accent);
  transform: translateY(-3px);
  text-shadow: 0 0 6px var(--color-accent);
}

/* Hover animation */
.bottom-nav a:hover i {
  transform: translateY(-3px);
}

/* Hide on desktop */
@media (min-width: 768px) {
  .bottom-nav {
    display: none;
  }
}
</style>
