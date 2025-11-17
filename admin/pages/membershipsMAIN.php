<?php
// admin/memberships.php
// Full page (all modals inline). Place in admin/ folder.

// ensure session & DB
require_once "../config/db.php";
require_once "../lib/functions.php";

// Fetch Members
$stmt = $pdo->query("
  SELECT u.*, m.discount_rate, m.status, m.start_date, m.end_date, m.membership_id, m.created_at as membership_created
  FROM users u 
  LEFT JOIN memberships m ON u.id = m.user_id 
  WHERE u.is_member = 1
  ORDER BY m.start_date DESC
");
$members = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch Non-Members (Users Tab)
$usersStmt = $pdo->query("SELECT * FROM users WHERE is_member = 0 ORDER BY created_at DESC");
$users = $usersStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Memberships | Admin Panel</title>
  <link rel="stylesheet" href="../../assets/css/app.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* tweaks specific to this page */
    .page-header {
      display:flex;
      justify-content:space-between;
      align-items:center;
      margin-bottom:1.2rem;
    }
    .btn-outline-primary {
      background:transparent;
      color:var(--color-primary);
      border:2px solid var(--color-primary);
      font-weight:600;
      transition:all .15s ease;
      padding:.45rem .8rem;
      border-radius:10px;
    }
    .btn-outline-primary:hover {
      background:var(--color-primary);
      color:var(--color-primary-content);
      transform:translateY(-2px);
    }

    .tab-btn {
      background:none;
      border:none;
      font-weight:600;
      padding:.45rem .9rem;
      cursor:pointer;
      border-bottom:3px solid transparent;
      color:var(--color-secondary);
      transition:0.2s ease-in-out;
    }
    .tab-btn.active {
      color:var(--color-primary);
      border-bottom:3px solid var(--color-primary);
    }

    /* global search (medium height) */
    .search-box { position:relative; }
    .search-box input.form-control-sm {
      padding-left:2rem;
      border-radius:30px;
      border:1px solid var(--color-base-300);
      width:230px;
      background:var(--color-base-200);
      height:34px;
    }
    .search-box i { position:absolute; left:10px; top:8px; color:var(--color-secondary); }

    /* cards */
    .member-card, .user-card {
      border-radius:1rem;
      border:1px solid var(--color-base-300);
      background:var(--color-base-200);
      transition: transform .22s ease, box-shadow .28s ease;
      height:100%;
      display:flex;
      flex-direction:column;
      justify-content:space-between;
    }
    .member-card:hover, .user-card:hover {
      transform: translateY(-6px);
      box-shadow:0 8px 20px rgba(0,0,0,0.08);
    }
    .member-avatar {
      width:70px;height:70px;border-radius:50%;
      background:var(--color-accent);
      color:white;display:flex;align-items:center;justify-content:center;
      font-weight:700;font-size:24px;margin:0 auto;
    }
    .badge-member {
      background:var(--color-primary); color:var(--color-primary-content);
      font-size:.75rem;border-radius:30px;padding:.25rem .6rem; display:inline-block;
    }

    .card-actions { display:flex; gap:.5rem; justify-content:center; margin-top:.8rem; }
    .card-actions .btn, .card-actions .icon-btn { font-size:.85rem; }

    .meta-small { font-size:.82rem; color:var(--color-secondary); }

    .hidden { display:none !important; }

    .sort-btn {
      background:transparent;border:1px solid var(--color-base-300);
      padding:.35rem .6rem;border-radius:.5rem;cursor:pointer;font-weight:600;
      display:inline-flex;align-items:center;gap:.3rem;
    }

    /* Rounded icon button (medium: 38x38) */
    .icon-btn {
      width:38px;
      height:38px;
      border-radius:50%;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      background:var(--color-primary);
      color:var(--color-primary-content);
      border:none;
      cursor:pointer;
      transition: all .18s ease;
      box-shadow: 0 4px 10px rgba(0,0,0,0.06);
    }
    .icon-btn i { font-size: 1.05rem; pointer-events: none; }
    /* on hover turn to outline */
    .icon-btn:hover {
      background: transparent;
      color: var(--color-primary);
      border: 2px solid var(--color-primary);
      transform: translateY(-3px);
    }
    .icon-btn:focus { outline: 3px solid rgba(0,0,0,0.06); }

    .user-action-small { display:flex; gap:.35rem; align-items:center; justify-content:center; }

    /* SweetAlert custom classes (applied via JS) */
    .swal2-custom-popup {
      border-radius: 12px !important;
    }
    .swal2-custom-button {
      border-radius: 8px !important;
      padding: 8px 18px !important;
      font-weight: 600 !important;
    }

    /* responsive tweaks for smaller screens */
    @media (max-width: 576px) {
      .search-box input.form-control-sm { width: 150px; }
    }
  </style>
</head>
<body>

<div class="container-fluid py-4">

  <div class="page-header">
    <h3><i class="bi bi-person-badge me-2"></i> Memberships</h3>
    <button class="btn-outline-primary" id="openRegisterBtn" data-bs-toggle="modal" data-bs-target="#makeMemberModal">
      <i class="bi bi-person-plus me-1"></i> Register New Member
    </button>
  </div>

  <div class="d-flex justify-content-between align-items-center mb-3">
    <div class="d-flex gap-2 align-items-center">
      <button id="tabUsers" class="tab-btn">Users</button>
      <button id="tabMembers" class="tab-btn active">Members</button>
    </div>

    <div class="d-flex gap-2 align-items-center">
      <div class="search-box" id="globalSearchBox">
        <i class="bi bi-search"></i>
        <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Search..." autocomplete="off">
      </div>

      <button id="sortBtn" class="sort-btn" title="Toggle sort">⇅ Sort</button>
    </div>
  </div>

  <!-- USERS TAB -->
  <div id="usersTab" style="display:none;">
    <div class="row g-3" id="usersGrid">
      <?php foreach ($users as $u): 
          $uName = sanitize($u['name']);
          $uEmail = sanitize($u['email']);
          $uPhone = sanitize($u['phone']);
          $initials = strtoupper(substr($uName,0,2));
          $created_at = $u['created_at'] ?? '';
      ?>
      <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 user-card-container" data-name="<?= strtolower($uName) ?>" data-created="<?= $created_at ?>">
        <div class="card p-3 user-card">
          <div>
            <div class="user-avatar mb-2"><?= $initials ?></div>
            <h6 class="text-center mb-1"><?= $uName ?></h6>
            <div class="text-center meta-small">
              <div><strong>Email:</strong> <?= $uEmail ?></div>
              <div><strong>Phone:</strong> <?= $uPhone ?></div>
            </div>
          </div>

          <div class="card-actions">
            <div class="icon-actions user-action-small">
              <!-- Add to membership -->
              <button title="Add to Membership" class="icon-btn add-member-btn" data-user-id="<?= $u['id'] ?>">
                <i class="bi bi-person-plus"></i>
              </button>

              <!-- Delete user -->
              <button title="Delete user" class="icon-btn delete-user-btn" data-user-id="<?= $u['id'] ?>">
                <i class="bi bi-trash"></i>
              </button>

              <!-- View details -->
              <button title="View details" class="icon-btn view-detail-btn" data-user='<?= json_encode([
                'id'=>$u['id'],
                'name'=>$uName,
                'email'=>$uEmail,
                'phone'=>$uPhone,
                'address'=>sanitize($u['address'] ?? '')
              ], JSON_HEX_APOS|JSON_HEX_QUOT) ?>'>
                <i class="bi bi-eye"></i>
              </button>
            </div>
          </div>

        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- MEMBERS TAB -->
  <div id="membersTab">
    <div class="row g-3" id="membersGrid">
      <?php foreach ($members as $m):
        $name = sanitize($m['name']);
        $initials = strtoupper(substr($name, 0, 2));
        $status = $m['status'] ?? 'Active';
        $end_date = $m['end_date'] ? date("M d, Y", strtotime($m['end_date'])) : 'N/A';
        $start_date = $m['start_date'] ? date("M d, Y", strtotime($m['start_date'])) : 'N/A';
        $discount = number_format($m['discount_rate'] ?? 3, 2) . "%";
        $membership_id = sanitize($m['membership_id'] ?? '');
        $created = $m['membership_created'] ?? ($m['created_at'] ?? '');
      ?>
      <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 member-card-container" data-name="<?= strtolower($name) ?>" data-created="<?= $created ?>" data-user-id="<?= $m['id'] ?>">
        <div class="card p-3 member-card">
          <div>
            <div class="member-avatar mb-2"><?= $initials ?></div>
            <h6 class="text-center mb-1"><?= $name ?></h6>
            <div class="text-center meta-small">
              <div><span class="badge-member"><?= $status ?></span></div>
              <div><small>Valid: </small><?= $start_date ?> — <?= $end_date ?></div>
              <div><small>Discount:</small> <?= $discount ?></div>
              <div><small>ID:</small> <?= $membership_id ?></div>
            </div>
          </div>

          <div class="card-actions">
            <div class="icon-actions">
              <button title="Remove membership" class="icon-btn remove-member-btn" data-user-id="<?= $m['id'] ?>"><i class="bi bi-trash"></i></button>
              <button title="View details" class="icon-btn view-detail-btn" data-user='<?= json_encode([
                'id'=>$m['id'],
                'name'=>$name,
                'email'=>sanitize($m['email'] ?? ''),
                'phone'=>sanitize($m['phone'] ?? ''),
                'address'=>sanitize($m['address'] ?? ''),
                'status'=>$status,
                'start_date'=>$m['start_date'] ?? '',
                'end_date'=>$m['end_date'] ?? ''
              ], JSON_HEX_APOS|JSON_HEX_QUOT) ?>'><i class="bi bi-eye"></i></button>
            </div>
          </div>

        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

</div>

<!-- ===========================
     MODALS (inline)
     - makeMemberModal (create new user + member) minimal scaffold
     - detailModal (view)
     - removeMemberVerifyModal (legacy, not used for admin password prompts but kept)
     - addExistingMemberModal (display-only user info, membership fields editable only)
   ============================ -->

<!-- makeMemberModal (you already have a full implementation elsewhere; minimal placeholder included) -->
<div class="modal fade" id="makeMemberModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <form class="modal-content" id="makeMemberForm" autocomplete="off">
      <div class="modal-header" style="background:var(--color-primary); color:var(--color-primary-content);">
        <h5 class="modal-title"><i class="bi bi-person-plus me-2"></i>Create & Register New Member</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <!-- NOTE: Full make-member implementation assumed elsewhere.
             Keep this modal as-is or replace with your existing make_member_modal.php content. -->
        <p class="text-muted small">Use your existing create-member modal here (this is a placeholder). Replace with full form implementation you already have.</p>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Close</button>
      </div>
    </form>
  </div>
</div>

<!-- Detail modal -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" autocomplete="off">
      <div class="modal-header" style="background:var(--color-primary); color:var(--color-primary-content);">
        <h5 class="modal-title"><i class="bi bi-person-lines-fill me-2"></i>User Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="detailBody"></div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Legacy verifyRemoveModal (kept for compatibility) -->
<div class="modal fade" id="verifyRemoveModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form class="modal-content" id="verifyRemoveForm" autocomplete="off">
      <div class="modal-header" style="background:var(--color-primary); color:var(--color-primary-content);">
        <h5 class="modal-title"><i class="bi bi-shield-lock me-2"></i>Confirm</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="remove_user_id" name="user_id">
        <div class="mb-2">
          <label class="form-label">Admin Password</label>
          <input type="password" id="remove_admin_password" name="admin_password" class="form-control form-control-sm" autocomplete="new-password" required>
        </div>
        <div id="remove_alert" class="mt-2"></div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Cancel</button>
        <button class="btn btn-danger" type="submit">Remove</button>
      </div>
    </form>
  </div>
</div>

<!-- Add Existing Member Modal
     NOTE: All user fields are display-only (readonly). The form ONLY submits:
     user_id (hidden), start_date (hidden), end_date (hidden), discount_rate (hidden).
     admin_password is added via SweetAlert before the POST and not rendered on the form.
-->
<div class="modal fade" id="addExistingMemberModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <form class="modal-content" id="addExistingMemberForm" autocomplete="off">
      <div class="modal-header" style="background:var(--color-primary); color:var(--color-primary-content);">
        <h5 class="modal-title"><i class="bi bi-person-plus me-2"></i>Add Existing User as Member</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <input type="hidden" name="user_id" id="add_user_id">

        <h6 class="fw-bold mb-2">User Information (display only)</h6>
        <div class="row g-2">
          <div class="col-md-4">
            <label class="form-label">First Name</label>
            <input type="text" id="add_first_name" class="form-control form-control-sm" readonly>
          </div>

          <div class="col-md-2">
            <label class="form-label">M.I.</label>
            <input type="text" id="add_middle_initial" class="form-control form-control-sm" readonly>
          </div>

          <div class="col-md-4">
            <label class="form-label">Last Name</label>
            <input type="text" id="add_last_name" class="form-control form-control-sm" readonly>
          </div>

          <div class="col-md-2">
            <label class="form-label">Suffix</label>
            <input type="text" id="add_suffix" class="form-control form-control-sm" readonly>
          </div>

          <div class="col-md-6 mt-2">
            <label class="form-label">Email Address</label>
            <input type="email" id="add_email" class="form-control form-control-sm" readonly>
          </div>

          <div class="col-md-6 mt-2">
            <label class="form-label">Phone Number</label>
            <input type="text" id="add_phone" class="form-control form-control-sm" readonly>
          </div>

          <div class="col-12 mt-2">
            <label class="form-label">Complete Address</label>
            <textarea id="add_address" class="form-control form-control-sm" rows="2" readonly></textarea>
          </div>
        </div>

        <hr class="my-3">

        <h6 class="fw-bold mb-2">Membership Details</h6>
        <div class="row g-2">
          <div class="col-md-6">
            <label class="form-label">Start Date</label>
            <!-- visible formatted date -->
            <input type="text" id="add_start_date_display" class="form-control form-control-sm" readonly>
            <!-- hidden actual date for DB -->
            <input type="hidden" id="add_start_date" name="start_date">
          </div>

          <div class="col-md-6">
            <label class="form-label">End Date</label>
            <input type="text" id="add_end_date_display" class="form-control form-control-sm" readonly>
            <input type="hidden" id="add_end_date" name="end_date">
          </div>

          <div class="col-md-6 mt-2">
            <label class="form-label">Discount Rate (%)</label>
            <!-- read-only hidden and visible -->
            <input type="text" id="add_discount_rate_display" class="form-control form-control-sm" value="3.00" readonly>
            <input type="hidden" id="add_discount_rate" name="discount_rate" value="3.00">
          </div>
        </div>

        <div id="add_existing_alert" class="mt-2"></div>

      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Cancel</button>
        <button class="btn btn-primary" id="add_existing_submit" type="submit">Add Member</button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
/* ---------- Utility ---------- */
function cssVar(name) {
  return getComputedStyle(document.documentElement).getPropertyValue(name).trim() || null;
}

/* ---------- SweetAlert defaults (themed) ---------- */
const swalDefaults = {
  customClass: { popup: 'swal2-custom-popup', confirmButton: 'swal2-custom-button', cancelButton: 'swal2-custom-button' },
  showClass: { popup: 'swal2-show' },
  hideClass: { popup: 'swal2-hide' },
  background: cssVar('--color-base-200') || '#ffffff',
  color: cssVar('--color-base-content') || '#222',
  confirmButtonColor: cssVar('--color-primary') || '#0d6efd',
  cancelButtonColor: 'transparent',
  buttonsStyling: false,
  showCancelButton: true,
  reverseButtons: true,
  focusCancel: true,
};
function themedSwal(opts) {
  return Swal.fire(Object.assign({}, swalDefaults, opts));
}

/* ---------- Tabs / Search / Sort ---------- */
const tabUsers = document.getElementById("tabUsers");
const tabMembers = document.getElementById("tabMembers");
const usersTab = document.getElementById("usersTab");
const membersTab = document.getElementById("membersTab");
const searchInput = document.getElementById("searchInput");
const sortBtn = document.getElementById("sortBtn");
let sortMode = 'newest';

tabUsers.onclick = () => {
  tabUsers.classList.add("active"); tabMembers.classList.remove("active");
  usersTab.style.display='block'; membersTab.style.display='none';
  filterCards(searchInput.value);
};
tabMembers.onclick = () => {
  tabMembers.classList.add("active"); tabUsers.classList.remove("active");
  membersTab.style.display='block'; usersTab.style.display='none';
  filterCards(searchInput.value);
};

searchInput.addEventListener('input', () => filterCards(searchInput.value));
function filterCards(q='') {
  const term = (q||'').trim().toLowerCase();
  document.querySelectorAll(".user-card-container").forEach(card => {
    const name = card.getAttribute("data-name") || '';
    card.style.display = name.includes(term) ? 'block' : 'none';
  });
  document.querySelectorAll(".member-card-container").forEach(card => {
    const name = card.getAttribute("data-name") || '';
    card.style.display = name.includes(term) ? 'block' : 'none';
  });
}

sortBtn.addEventListener('click', () => {
  sortMode = sortMode === 'newest' ? 'az' : 'newest';
  sortBtn.textContent = sortMode === 'newest' ? '⇅ Sort' : 'A→Z';
  applySort();
});

function applySort(){
  const usersGrid = document.getElementById('usersGrid');
  if (usersGrid) {
    const nodes = Array.from(usersGrid.children);
    nodes.sort((a,b)=> sortMode==='az' ? a.getAttribute('data-name').localeCompare(b.getAttribute('data-name'))
      : (b.getAttribute('data-created')||'').localeCompare(a.getAttribute('data-created')||''));
    nodes.forEach(n=> usersGrid.appendChild(n));
  }
  const membersGrid = document.getElementById('membersGrid');
  if (membersGrid) {
    const nodes = Array.from(membersGrid.children);
    nodes.sort((a,b)=> sortMode==='az' ? a.getAttribute('data-name').localeCompare(b.getAttribute('data-name'))
      : (b.getAttribute('data-created')||'').localeCompare(a.getAttribute('data-created')||''));
    nodes.forEach(n=> membersGrid.appendChild(n));
  }
}

/* ---------- Add Existing User -> Membership ---------- */
/* Flow:
   1) Click add button -> Step 1 confirm via Swal
   2) Fetch user -> populate display-only fields + set hidden start/end date values
   3) Show bootstrap modal so admin can preview membership dates (display-only text + hidden inputs)
   4) Submit modal -> Swal asks admin password (blank) -> send POST with minimal fields
*/

document.addEventListener('click', async function(e) {
  if (!e.target.closest || !e.target.closest('.add-member-btn')) return;
  const btn = e.target.closest('.add-member-btn');
  const uid = btn.getAttribute('data-user-id');

  const confirm = await themedSwal({
    title: 'Add to Membership',
    html: '<p>Convert this existing user into a member?<br/><small>This will create a membership record for the user.</small></p>',
    icon: 'question',
    confirmButtonText: 'Continue',
    cancelButtonText: 'Cancel'
  });
  if (!confirm || !confirm.isConfirmed) return;

  // fetch user details
  try {
    const res = await fetch('functions/get_user.php?user_id=' + encodeURIComponent(uid), { cache: 'no-store' });
    const json = await res.json();
    if (!json.success) {
      themedSwal({ title: 'Failed', html: json.message || 'Unable to load user', icon: 'error', showCancelButton: false });
      return;
    }

    const user = json.user || {};
    // populate display-only fields (no name attributes for these display-only fields)
    document.getElementById('add_user_id').value = user.id || '';
    document.getElementById('add_first_name').value = user.first_name || '';
    document.getElementById('add_middle_initial').value = user.middle_initial || '';
    document.getElementById('add_last_name').value = user.last_name || '';
    document.getElementById('add_suffix').value = user.suffix || '';
    document.getElementById('add_email').value = user.email || '';
    document.getElementById('add_phone').value = user.phone || '';
    document.getElementById('add_address').value = user.address || '';

    // default membership dates (hidden inputs raw, visible inputs formatted)
    const start = new Date();
    const end = new Date(); end.setMonth(end.getMonth() + 6);

    // hidden ISO strings
    const startISO = start.toISOString().slice(0,10);
    const endISO = end.toISOString().slice(0,10);
    document.getElementById('add_start_date').value = startISO;
    document.getElementById('add_end_date').value = endISO;

    // formatted visible strings
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    document.getElementById('add_start_date_display').value = start.toLocaleDateString('en-US', options);
    document.getElementById('add_end_date_display').value = end.toLocaleDateString('en-US', options);

    // discount read-only default
    document.getElementById('add_discount_rate').value = '3.00';
    document.getElementById('add_discount_rate_display').value = '3.00';

    // show modal
    const modal = new bootstrap.Modal(document.getElementById('addExistingMemberModal'));
    modal.show();

  } catch (err) {
    console.error(err);
    themedSwal({ title: 'Server error', text: 'Failed to fetch user details', icon: 'error', showCancelButton: false });
  }
});

/* Submit add-existing-member form */
document.getElementById('addExistingMemberForm').addEventListener('submit', async function(e){
  e.preventDefault();
  const form = e.target;

  // Step: require admin password via SweetAlert (blank input)
  const pwResult = await themedSwal({
    title: 'Confirm Admin Password',
    input: 'password',
    inputPlaceholder: 'Enter admin password',
    inputAttributes: { autocapitalize: 'off', autocorrect: 'off', autocomplete: 'new-password' },
    showCancelButton: true,
    confirmButtonText: 'Confirm',
    cancelButtonText: 'Cancel',
    preConfirm: (pw) => {
      if (!pw) { Swal.showValidationMessage('Password is required'); return false; }
      return pw;
    }
  });
  if (!pwResult || !pwResult.value) return;
  const admin_password = pwResult.value;

  // Build minimal payload
  const payload = new FormData();
  payload.append('user_id', document.getElementById('add_user_id').value || '');
  payload.append('start_date', document.getElementById('add_start_date').value || '');
  payload.append('end_date', document.getElementById('add_end_date').value || '');
  payload.append('discount_rate', document.getElementById('add_discount_rate').value || '3.00');
  payload.append('admin_password', admin_password);

  const submitBtn = document.getElementById('add_existing_submit');
  submitBtn.disabled = true; submitBtn.textContent = 'Processing...';

  try {
    const res = await fetch('functions/add_existing_member.php', { method: 'POST', body: payload });
    const json = await res.json();
    if (json.status === 'success' || json.success) {
      bootstrap.Modal.getInstance(document.getElementById('addExistingMemberModal')).hide();
      themedSwal({ title: 'Success', text: json.message || 'Member added', icon: 'success', showCancelButton: false, confirmButtonText: 'OK' });
      setTimeout(()=> location.reload(), 700);
    } else {
      document.getElementById('add_existing_alert').innerHTML = `<div class="text-danger small">${json.message || 'Failed'}</div>`;
      themedSwal({ title: 'Failed', text: json.message || 'Failed to add member', icon: 'error', showCancelButton: false });
    }
  } catch (err) {
    console.error(err);
    document.getElementById('add_existing_alert').innerHTML = `<div class="text-danger small">Server error</div>`;
    themedSwal({ title: 'Server error', text: 'Could not reach server', icon: 'error', showCancelButton: false });
  } finally {
    submitBtn.disabled = false; submitBtn.textContent = 'Add Member';
  }
});

/* ---------- Delete user (two-step SweetAlert) ---------- */
document.addEventListener('click', function(e){
  if (!e.target.closest || !e.target.closest('.delete-user-btn')) return;
  const btn = e.target.closest('.delete-user-btn');
  const uid = btn.getAttribute('data-user-id');

  (async () => {
    const confirm = await themedSwal({
      title: 'Delete User',
      html: `<p>Are you sure you want to <strong>permanently delete</strong> this user and all related data?<br><small>This action cannot be undone.</small></p>`,
      icon: 'warning',
      confirmButtonText: 'Yes, delete',
      cancelButtonText: 'Cancel'
    });
    if (!confirm || !confirm.isConfirmed) return;

    const pwResult = await themedSwal({
      title: 'Admin Password',
      input: 'password',
      inputPlaceholder: 'Enter admin password to confirm',
      inputAttributes: { autocomplete: 'new-password' },
      showCancelButton: true,
      confirmButtonText: 'Delete',
      cancelButtonText: 'Cancel',
      preConfirm: (pw) => { if (!pw) { Swal.showValidationMessage('Password is required'); return false; } return pw; }
    });
    if (!pwResult || !pwResult.value) return;
    const admin_password = pwResult.value;

    try {
      const res = await fetch('functions/delete_user.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ user_id: uid, admin_password })
      });
      const json = await res.json();
      if (json.success) {
        themedSwal({ title: 'Deleted', text: json.message || 'User deleted', icon: 'success', showCancelButton: false });
        setTimeout(()=> location.reload(), 700);
      } else {
        themedSwal({ title: 'Failed', text: json.message || 'Could not delete user', icon: 'error', showCancelButton: false });
      }
    } catch (err) {
      console.error(err);
      themedSwal({ title: 'Server error', text: 'Failed to contact server', icon: 'error', showCancelButton: false });
    }
  })();
});

/* ---------- Remove membership (two-step SweetAlert) ---------- */
document.addEventListener('click', function(e){
  if (!e.target.closest || !e.target.closest('.remove-member-btn')) return;
  const btn = e.target.closest('.remove-member-btn');
  const uid = btn.getAttribute('data-user-id');

  (async () => {
    const confirm = await themedSwal({
      title: 'Remove Membership',
      html: `<p>Are you sure you want to remove membership for this user?</p>`,
      icon: 'question',
      confirmButtonText: 'Continue',
      cancelButtonText: 'Cancel'
    });
    if (!confirm || !confirm.isConfirmed) return;

    const pwResult = await themedSwal({
      title: 'Admin Password',
      input: 'password',
      inputPlaceholder: 'Enter admin password to confirm',
      inputAttributes: { autocomplete: 'new-password' },
      showCancelButton: true,
      confirmButtonText: 'Remove',
      cancelButtonText: 'Cancel',
      preConfirm: (pw) => { if (!pw) { Swal.showValidationMessage('Password required'); return false; } return pw; }
    });
    if (!pwResult || !pwResult.value) return;
    const admin_password = pwResult.value;

    try {
      const res = await fetch('functions/remove_member.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ user_id: uid, admin_password })
      });
      const json = await res.json();
      if (json.success || json.status === 'success') {
        themedSwal({ title: 'Removed', text: json.message || 'Membership removed', icon: 'success', showCancelButton: false });
        setTimeout(()=> location.reload(), 700);
      } else {
        themedSwal({ title: 'Failed', text: json.message || 'Could not remove membership', icon: 'error', showCancelButton: false });
      }
    } catch (err) {
      console.error(err);
      themedSwal({ title: 'Server error', text: 'Failed to contact server', icon: 'error', showCancelButton: false });
    }
  })();
});

/* ---------- View detail modal ---------- */
document.addEventListener('click', function(e){
  if (!(e.target.closest && e.target.closest('.view-detail-btn'))) return;
  const btn = e.target.closest('.view-detail-btn');
  const userJson = btn.getAttribute('data-user');
  let user;
  try { user = JSON.parse(userJson); } catch(err) { user = null; }
  if (!user) return;
  const body = `
    <p><strong>Name:</strong> ${user.name || ''}</p>
    <p><strong>Email:</strong> ${user.email || ''}</p>
    <p><strong>Phone:</strong> ${user.phone || ''}</p>
    <p><strong>Address:</strong> ${user.address || ''}</p>
  `;
  document.getElementById('detailBody').innerHTML = body;
  new bootstrap.Modal(document.getElementById('detailModal')).show();
});
</script>

</body>
</html>
