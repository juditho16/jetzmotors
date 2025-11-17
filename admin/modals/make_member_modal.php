<?php
// admin/modals/make_member_modal.php

date_default_timezone_set('Asia/Manila');
$startDate = date("Y-m-d");
$endDate   = date("Y-m-d", strtotime("+6 months"));
?>

<div class="modal fade" id="makeMemberModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">

    <!-- ⚠ FORM DOES NOT SUBMIT DIRECTLY ANYMORE -->
    <form class="modal-content" id="makeMemberForm" autocomplete="off" novalidate>

      <!-- Autofill Trap -->
      <input type="text" style="display:none">
      <input type="password" style="display:none">

      <div class="modal-header" style="background:var(--color-primary); color:white;">
        <h5 class="modal-title">
          <i class="bi bi-person-plus me-2"></i>Create & Register New Member
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <h6 class="fw-bold mb-2">User Information</h6>

        <div class="row g-2">

          <div class="col-md-4">
            <label class="form-label">First Name</label>
            <input type="text" name="first_name" class="form-control form-control-sm" required>
          </div>

          <div class="col-md-2">
            <label class="form-label">M.I.</label>
            <input type="text" name="middle_initial" class="form-control form-control-sm">
          </div>

          <div class="col-md-4">
            <label class="form-label">Last Name</label>
            <input type="text" name="last_name" class="form-control form-control-sm" required>
          </div>

          <div class="col-md-2">
            <label class="form-label">Suffix</label>
            <input type="text" name="suffix" class="form-control form-control-sm">
          </div>

          <div class="col-md-6 mt-2">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control form-control-sm" required>
          </div>

          <div class="col-md-6 mt-2">
            <label class="form-label">Phone Number</label>
            <input type="text" name="phone" class="form-control form-control-sm" required>
          </div>

          <div class="col-12 mt-2">
            <label class="form-label">Complete Address</label>
            <input type="text" name="address" class="form-control form-control-sm" required>
          </div>

          <!-- Password (New User) -->
          <div class="col-md-6 mt-2">
            <label class="form-label">Password</label>
            <input 
              type="password" 
              name="password"
              class="form-control form-control-sm"
              required
              autocomplete="new-password"
            >
          </div>

          <div class="col-md-6 mt-2">
            <label class="form-label">Re-enter Password</label>
            <input 
              type="password" 
              name="password_verify"
              class="form-control form-control-sm"
              required
              autocomplete="new-password"
            >
          </div>
        </div>

        <hr class="my-3">

        <h6 class="fw-bold mb-2">Membership Details</h6>

        <div class="row g-2">
          <div class="col-md-6">
            <label class="form-label">Start Date</label>
            <input type="text" class="form-control form-control-sm" value="<?php echo date("F d, Y"); ?>" readonly>
            <input type="hidden" name="start_date" value="<?php echo $startDate; ?>">
          </div>

          <div class="col-md-6">
            <label class="form-label">End Date</label>
            <input type="text" class="form-control form-control-sm" value="<?php echo date("F d, Y", strtotime("+6 months")); ?>" readonly>
            <input type="hidden" name="end_date" value="<?php echo $endDate; ?>">
          </div>

          <div class="col-md-6 mt-2">
            <label class="form-label">Discount Rate (%)</label>
            <input type="text" class="form-control form-control-sm" value="3.00" readonly>
            <input type="hidden" name="discount_rate" value="3.00">
          </div>
        </div>

        <div id="make_member_alert" class="mt-3"></div>

      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary" id="make_member_submit" type="submit">
          Create Member
        </button>
      </div>

    </form>

  </div>
</div>

<script>
// Always clear passwords when opening modal
document.getElementById('makeMemberModal').addEventListener('show.bs.modal', () => {
  document.querySelectorAll('#makeMemberModal input[type=password]').forEach(f => f.value = "");
});
</script>
