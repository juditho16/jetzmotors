<?php
$members = $pdo->query("SELECT * FROM users WHERE is_member=1 ORDER BY member_until DESC")->fetchAll();
?>

<div class="container-fluid">
  <h1 class="mb-4"><i class="bi bi-person-badge me-2"></i> Memberships</h1>

  <table class="table table-striped">
    <thead class="table-dark">
      <tr>
        <th>Name</th><th>Email</th><th>Phone</th><th>Status</th><th>Valid Until</th><th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($members as $m): ?>
      <tr>
        <td><?= sanitize($m['name']) ?></td>
        <td><?= sanitize($m['email']) ?></td>
        <td><?= sanitize($m['phone']) ?></td>
        <td>
          <?php $active = is_member_active($m['is_member'],$m['member_until']); ?>
          <span class="badge bg-<?= $active ? "success" : "danger" ?>">
            <?= $active ? "Active" : "Expired" ?>
          </span>
        </td>
        <td><?= $m['member_until'] ? date("M d, Y", strtotime($m['member_until'])) : "N/A" ?></td>
        <td>
          <a href="../functions/renew_membership.php?id=<?= $m['id'] ?>" class="btn btn-sm btn-warning">Renew</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
