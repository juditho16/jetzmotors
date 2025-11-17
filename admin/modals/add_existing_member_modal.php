<?php
// admin/modals/add_existing_member_modal.php
date_default_timezone_set('Asia/Manila');
?>
<div class="modal fade" id="addExistingMemberModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <form class="modal-content" id="addExistingMemberForm">

      <div class="modal-header" style="background:var(--color-primary); color:var(--color-primary-content);">
        <h5 class="modal-title">
          <i class="bi bi-person-plus me-2"></i>Add Existing User as Member
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <!-- Hidden User ID -->
        <input type="hidden" name="user_id" id="add_user_id">

        <!-- USER INFORMATION -->
        <h6 class="fw-bold mb-2">User Information</h6>

        <div class="row g-2">

          <div class="col-md-4">
            <label class="form-label">First Name</label>
            <input type="text" id="add_first_name" name="first_name"
                   class="form-control form-control-sm" readonly>
          </div>

          <div class="col-md-2">
            <label class="form-label">M.I.</label>
            <input type="text" id="add_middle_initial" name="middle_initial"
                   class="form-control form-control-sm" readonly>
          </div>

          <div class="col-md-4">
            <label class="form-label">Last Name</label>
            <input type="text" id="add_last_name" name="last_name"
                   class="form-control form-control-sm" readonly>
          </div>

          <div class="col-md-2">
            <label class="form-label">Suffix</label>
            <input type="text" id="add_suffix" name="suffix"
                   class="form-control form-control-sm" readonly>
          </div>

          <div class="col-md-6 mt-2">
            <label class="form-label">Email Address</label>
            <input type="email" id="add_email" name="email"
                   class="form-control form-control-sm" readonly>
          </div>

          <div class="col-md-6 mt-2">
            <label class="form-label">Phone Number</label>
            <input type="text" id="add_phone" name="phone"
                   class="form-control form-control-sm" readonly>
          </div>

          <div class="col-12 mt-2">
            <label class="form-label">Complete Address</label>
            <textarea id="add_address" name="address"
                      class="form-control form-control-sm" rows="2" readonly></textarea>
          </div>

        </div>

        <hr class="my-3">

        <!-- MEMBERSHIP SETTINGS -->
        <h6 class="fw-bold mb-2">Membership Details</h6>

        <div class="row g-2">

          <!-- START DATE -->
          <div class="col-md-6">
            <label class="form-label">Start Date</label>

            <!-- Visible formatted date -->
            <input type="text" id="add_start_date_display"
                   class="form-control form-control-sm" readonly>

            <!-- Hidden actual DB date -->
            <input type="hidden" id="add_start_date" name="start_date">
          </div>

          <!-- END DATE -->
          <div class="col-md-6">
            <label class="form-label">End Date</label>

            <input type="text" id="add_end_date_display"
                   class="form-control form-control-sm" readonly>

            <input type="hidden" id="add_end_date" name="end_date">
          </div>

          <!-- DISCOUNT RATE -->
          <div class="col-md-6 mt-2">
            <label class="form-label">Discount Rate (%)</label>
            <input type="text" id="add_discount_rate" name="discount_rate"
                   class="form-control form-control-sm" value="3.00" readonly>
          </div>

        </div>

        <hr class="my-3">

        <div id="add_existing_alert" class="mt-2"></div>

      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Cancel</button>
        <button class="btn btn-primary" id="add_existing_submit" type="submit">Add Member</button>
      </div>

    </form>
  </div>
</div>
