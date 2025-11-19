<?php
// /admin/modals/pos_member_modal.php
?>

<!-- Member Modal -->
<div class="modal fade" id="memberModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header py-2">
                <h5 class="modal-title"><i class="bi bi-people me-2"></i> Select Member</h5>
                <button type="button" class="btn-close"></button>
            </div>

            <div class="modal-body">
                <input type="text" id="memberSearch" class="form-control form-control-sm mb-2"
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
