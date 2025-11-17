<?php
// admin/modals/global_admin_password_modal.php
// Global Admin Password Modal (Bootstrap) - Anti-autofill protection included
?>
<!-- Global Admin Password Modal -->
<div class="modal fade" id="globalAdminModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form id="globalAdminForm" class="modal-content" autocomplete="off" novalidate>
      <div class="modal-header" style="background:var(--color-primary); color:var(--color-primary-content); border-top-left-radius: .5rem; border-top-right-radius: .5rem;">
        <h5 class="modal-title"><i class="bi bi-shield-lock me-2"></i>Admin Verification</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <!-- Hidden dummy inputs to defeat browser autofill -->
        <input type="text" name="fake_username_autofill" id="fake_username_autofill" value="" style="display:none!important" autocomplete="off">
        <input type="password" name="fake_password_autofill" id="fake_password_autofill" value="" style="display:none!important" autocomplete="new-password">

        <p class="small text-muted">Enter your admin password to confirm this action.</p>

        <div class="mb-2">
          <label class="form-label">Admin Password</label>
          <!-- set autocomplete to new-password and explicit attributes to reduce autofill -->
          <input type="password" id="global_admin_password_input" class="form-control form-control-sm" autocomplete="new-password" required>
        </div>

        <div id="global_admin_alert" class="mt-2"></div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button id="global_admin_submit" type="submit" class="btn btn-primary">Verify</button>
      </div>
    </form>
  </div>
</div>
