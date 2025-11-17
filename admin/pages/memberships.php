<?php
require_once "../config/db.php";
require_once "../lib/functions.php";

/* Fetch Members */
$stmt = $pdo->query("
  SELECT u.*, m.discount_rate, m.status, m.start_date, m.end_date, m.membership_id,
         m.created_at AS membership_created
  FROM users u
  LEFT JOIN memberships m ON u.id = m.user_id
  WHERE u.is_member = 1
  ORDER BY m.start_date DESC
");
$members = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* Fetch Non-Members */
$usersStmt = $pdo->query("SELECT * FROM users WHERE is_member = 0 ORDER BY created_at DESC");
$users = $usersStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Memberships | Admin Panel</title>

  <link rel="stylesheet" href="../../assets/css/app.css">
  <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    .page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:1.2rem; }

    .btn-outline-primary {
      background:transparent; color:var(--color-primary);
      border:2px solid var(--color-primary); font-weight:600;
      transition:.15s; padding:.45rem .8rem; border-radius:10px;
    }
    .btn-outline-primary:hover { background:var(--color-primary); color:var(--color-primary-content); transform:translateY(-2px); }

    .tab-btn {
      background:none; border:none; font-weight:600;
      padding:.45rem .9rem; cursor:pointer;
      border-bottom:3px solid transparent; transition:.2s;
      color:var(--color-secondary);
    }
    .tab-btn.active { color:var(--color-primary); border-bottom:3px solid var(--color-primary); }

    .search-box { position:relative; }
    .search-box input { padding-left:2rem; border-radius:30px; height:34px; }
    .search-box i { position:absolute; left:10px; top:8px; }

    .sort-btn {
      background:transparent; border:1px solid var(--color-base-300);
      padding:.35rem .6rem; border-radius:.5rem;
      font-weight:600; cursor:pointer;
    }
  </style>
</head>

<body>

<div class="container-fluid py-4">

  <div class="page-header">
    <h3><i class="bi bi-person-badge me-2"></i> Memberships</h3>
    <button class="btn-outline-primary" data-bs-toggle="modal" data-bs-target="#makeMemberModal">
      <i class="bi bi-person-plus me-1"></i> Register New Member
    </button>
  </div>

  <div class="d-flex justify-content-between align-items-center mb-3">

    <div class="d-flex gap-2">
      <button id="tabUsers" class="tab-btn">Users</button>
      <button id="tabMembers" class="tab-btn active">Members</button>
    </div>

    <div class="d-flex gap-2 align-items-center">
      <div class="search-box">
        <i class="bi bi-search"></i>
        <input type="text" id="searchInput" class="form-control form-control-sm"
               placeholder="Search..." autocomplete="off">
      </div>
      <button id="sortBtn" class="sort-btn">⇅ Sort</button>
    </div>

  </div>

  <div id="usersTab" style="display:none;">
    <?php include "membership_users.php"; ?>
  </div>

  <div id="membersTab">
    <?php include "membership_members.php"; ?>
  </div>

</div>

<!-- ALL MODALS -->
<?php
// Global admin verify modal
include "modals/global_admin_password_modal.php";
// Add existing member modal (preview then global verify)
include "modals/add_existing_member_modal.php";
// Make member modal (create new user + membership)
include "modals/make_member_modal.php";
// Detail modal for view
include "modals/detail_modal.php";
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
/* ============================================================
   UI HELPERS
   ============================================================ */
function cssVar(n){ return getComputedStyle(document.documentElement).getPropertyValue(n).trim(); }

const swalDefaults = {
  customClass:{
    popup:'swal2-custom-popup',
    confirmButton:'swal2-custom-button',
    cancelButton:'swal2-custom-button'
  },
  background:cssVar('--color-base-200'),
  color:cssVar('--color-base-content'),
  confirmButtonColor:cssVar('--color-primary'),
  cancelButtonColor:'transparent',
  buttonsStyling:false,
  showCancelButton:true,
  reverseButtons:true,
  focusCancel:true
};
const themedSwal = o => Swal.fire(Object.assign({}, swalDefaults, o));

/* ============================================================
   TABS / SEARCH / SORT
   ============================================================ */
const tabUsers=document.getElementById("tabUsers");
const tabMembers=document.getElementById("tabMembers");
const usersTab=document.getElementById("usersTab");
const membersTab=document.getElementById("membersTab");
const searchInput=document.getElementById("searchInput");

tabUsers.onclick=()=>{ 
  tabUsers.classList.add("active"); 
  tabMembers.classList.remove("active");
  usersTab.style.display='block'; 
  membersTab.style.display='none'; 
  filterCards(searchInput.value); 
};

tabMembers.onclick=()=>{ 
  tabMembers.classList.add("active"); 
  tabUsers.classList.remove("active");
  membersTab.style.display='block'; 
  usersTab.style.display='none'; 
  filterCards(searchInput.value); 
};

function filterCards(q){
  q=(q||'').toLowerCase();
  document.querySelectorAll('.user-card-container').forEach(c=>{
    c.style.display = (c.dataset.name||'').includes(q) ? 'block' : 'none';
  });
  document.querySelectorAll('.member-card-container').forEach(c=>{
    c.style.display = (c.dataset.name||'').includes(q) ? 'block' : 'none';
  });
}
searchInput.addEventListener("input",()=>filterCards(searchInput.value));

let sortMode='newest';
document.getElementById("sortBtn").onclick=()=>{ sortMode=(sortMode==='newest'?'az':'newest'); applySort(); };

function applySort(){
  const sortGrid = g=>{
    if(!g) return;
    [...g.children].sort((a,b)=>{
      return sortMode==='az'
        ? (a.dataset.name||'').localeCompare(b.dataset.name||'')
        : (b.dataset.created||'').localeCompare(a.dataset.created||'');
    }).forEach(n=>g.appendChild(n));
  };
  sortGrid(document.getElementById('usersGrid'));
  sortGrid(document.getElementById('membersGrid'));
}

/* ============================================================
   GLOBAL ADMIN PASSWORD WORKFLOW
   - Global modal verifies admin.
   - After successful verify we perform the queued action.
   ============================================================ */

let __pendingAdminAction = null;

function openGlobalAdminModal(action, payload){
  __pendingAdminAction = { action, payload };
  const input = document.getElementById('global_admin_password_input');
  const alert = document.getElementById('global_admin_alert');
  if(input) input.value = '';
  if(alert) alert.innerHTML = '';
  new bootstrap.Modal(document.getElementById('globalAdminModal')).show();
}

async function verifyAdminPassword(pwd){
  try {
    const r = await fetch('functions/verify_admin_password.php', {
      method:'POST', headers:{'Content-Type':'application/json'},
      body: JSON.stringify({ password: pwd })
    });
    return await r.json();
  } catch (err) {
    return { success:false, message:'Network error' };
  }
}

/* perform pending action after verification */
async function performPendingAction(){
  if(!__pendingAdminAction) return { success:false, message:"No action queued." };

  const { action, payload } = __pendingAdminAction;

  try {
    if(action === 'delete_user'){
      const r = await fetch('functions/delete_user.php', {
        method:'POST',
        headers:{ 'Content-Type':'application/json' },
        body: JSON.stringify({ user_id: payload.user_id, admin_password: '__VERIFIED__' })
      });
      return await r.json();
    }

    if(action === 'remove_member'){
      const r = await fetch('functions/remove_member.php', {
        method:'POST',
        headers:{ 'Content-Type':'application/json' },
        body: JSON.stringify({ user_id: payload.user_id, admin_password: '__VERIFIED__' })
      });
      return await r.json();
    }

    if(action === 'add_existing_member'){
      // payload has user_id, start_date, end_date, discount_rate
      const fd = new FormData();
      fd.append('user_id', payload.user_id || '');
      fd.append('start_date', payload.start_date || '');
      fd.append('end_date', payload.end_date || '');
      fd.append('discount_rate', payload.discount_rate || '3.00');

      const r = await fetch('functions/add_existing_member.php', { method:'POST', body: fd });
      return await r.json();
    }

    if(action === 'create_new_member'){
      // payload contains the make member form fields
      const fd = new FormData();
      fd.append('first_name', payload.first_name || '');
      fd.append('middle_initial', payload.middle_initial || '');
      fd.append('last_name', payload.last_name || '');
      fd.append('suffix', payload.suffix || '');
      fd.append('email', payload.email || '');
      fd.append('phone', payload.phone || '');
      fd.append('address', payload.address || '');
      fd.append('password', payload.password || '');
      fd.append('password_verify', payload.password_verify || '');
      fd.append('start_date', payload.start_date || '');
      fd.append('end_date', payload.end_date || '');
      fd.append('discount_rate', payload.discount_rate || '3.00');

      const r = await fetch('functions/create_new_member.php', { method:'POST', body: fd });
      return await r.json();
    }

    return { success:false, message:'Unknown action' };

  } catch (err) {
    return { success:false, message: err.message || 'Network error' };
  }
}

/* Global modal submit handler */
const globalAdminForm = document.getElementById('globalAdminForm');
if(globalAdminForm){
  globalAdminForm.addEventListener('submit', async ev => {
    ev.preventDefault();
    const input = document.getElementById('global_admin_password_input');
    const alert = document.getElementById('global_admin_alert');
    const submitBtn = document.getElementById('global_admin_submit');

    const pwd = input ? input.value.trim() : '';
    if(!pwd){
      if(alert) alert.innerHTML = '<div class="text-danger small">Password is required.</div>';
      return;
    }

    if(submitBtn) { submitBtn.disabled = true; submitBtn.textContent = 'Verifying…'; }

    const verify = await verifyAdminPassword(pwd);

    if(!verify.success){
      if(alert) alert.innerHTML = `<div class="text-danger small">${verify.message || 'Invalid password'}</div>`;
      if(submitBtn) { submitBtn.disabled = false; submitBtn.textContent = 'Verify'; }
      return;
    }

    // close modal
    const bs = bootstrap.Modal.getInstance(document.getElementById('globalAdminModal'));
    if(bs) bs.hide();

    themedSwal({ icon:'info', title:'Processing…', showConfirmButton:false, timer:700 });

    if(submitBtn) { submitBtn.disabled = false; submitBtn.textContent = 'Verify'; }

    const result = await performPendingAction();

    if(result && (result.success || result.status === 'success')){
      themedSwal({ icon:'success', title:'Success', text: result.message || 'Done', showCancelButton:false });
      setTimeout(()=> location.reload(), 700);
    } else {
      themedSwal({ icon:'error', title:'Failed', text: (result && (result.message || result.error)) || 'Action failed' });
    }
  });
}

/* ============================================================
   ACTION BUTTON HANDLERS
   - Add existing member -> opens preview modal then global verify
   - Make member (create new) -> opens makeMemberModal (already in DOM). On submit we open global verify with payload
   - Delete / Remove -> global verify
   ============================================================ */

/* ---------- ADD EXISTING MEMBER (preview) ---------- */
document.addEventListener("click", async e=>{
  const btn = e.target.closest(".add-member-btn");
  if(!btn) return;

  const userId = btn.dataset.userId;
  const c = await themedSwal({
    icon:"question", title:"Add to Membership",
    html:"Convert this user into a member?", confirmButtonText:"Continue"
  });
  if(!c.isConfirmed) return;

  const res = await fetch("functions/get_user.php?user_id="+encodeURIComponent(userId));
  const json = await res.json();
  if(!json.success){ themedSwal({icon:"error",title:"Error",text:"Failed to fetch user."}); return; }

  const u = json.user || {};

  // populate addExistingMember modal
  document.getElementById("add_user_id").value = u.id || '';
  document.getElementById("add_first_name").value = u.first_name || '';
  document.getElementById("add_middle_initial").value = u.middle_initial || '';
  document.getElementById("add_last_name").value = u.last_name || '';
  document.getElementById("add_suffix").value = u.suffix || '';
  document.getElementById("add_email").value = u.email || '';
  document.getElementById("add_phone").value = u.phone || '';
  document.getElementById("add_address").value = u.address || '';

  const start = new Date(); const end = new Date(); end.setMonth(end.getMonth()+6);
  document.getElementById("add_start_date").value = start.toISOString().slice(0,10);
  document.getElementById("add_end_date").value = end.toISOString().slice(0,10);
  const opt = {year:"numeric", month:"long", day:"numeric"};
  document.getElementById("add_start_date_display").value = start.toLocaleDateString("en-US", opt);
  document.getElementById("add_end_date_display").value = end.toLocaleDateString("en-US", opt);
  document.getElementById("add_discount_rate").value = "3.00";

  new bootstrap.Modal(document.getElementById("addExistingMemberModal")).show();
});

/* When addExistingMemberForm submitted -> queue action for global verify */
const addExistingMemberForm = document.getElementById("addExistingMemberForm");
if(addExistingMemberForm){
  addExistingMemberForm.addEventListener("submit", e=>{
    e.preventDefault();
    const payload = {
      user_id: document.getElementById("add_user_id").value,
      start_date: document.getElementById("add_start_date").value,
      end_date: document.getElementById("add_end_date").value,
      discount_rate: document.getElementById("add_discount_rate").value
    };
    // hide preview modal
    const m = bootstrap.Modal.getInstance(document.getElementById("addExistingMemberModal"));
    if(m) m.hide();
    openGlobalAdminModal("add_existing_member", payload);
  });
}

/* ---------- MAKE MEMBER (create new user + membership) ---------- */
/* makeMemberModal is present in DOM (make_member_modal.php) */
const makeMemberForm = document.getElementById('makeMemberForm');
if(makeMemberForm){
  makeMemberForm.addEventListener('submit', e=>{
    e.preventDefault();

    // gather fields
    const fd = {};
    fd.first_name = (makeMemberForm.querySelector('input[name="first_name"]')||{}).value || '';
    fd.middle_initial = (makeMemberForm.querySelector('input[name="middle_initial"]')||{}).value || '';
    fd.last_name = (makeMemberForm.querySelector('input[name="last_name"]')||{}).value || '';
    fd.suffix = (makeMemberForm.querySelector('input[name="suffix"]')||{}).value || '';
    fd.email = (makeMemberForm.querySelector('input[name="email"]')||{}).value || '';
    fd.phone = (makeMemberForm.querySelector('input[name="phone"]')||{}).value || '';
    fd.address = (makeMemberForm.querySelector('input[name="address"]')||{}).value || '';
    fd.password = (makeMemberForm.querySelector('input[name="password"]')||{}).value || '';
    fd.password_verify = (makeMemberForm.querySelector('input[name="password_verify"]')||{}).value || '';
    fd.start_date = (makeMemberForm.querySelector('input[name="start_date"]')||{}).value || (new Date()).toISOString().slice(0,10);
    fd.end_date = (makeMemberForm.querySelector('input[name="end_date"]')||{}).value || (function(){ const d=new Date(); d.setMonth(d.getMonth()+6); return d.toISOString().slice(0,10); })();
    fd.discount_rate = (makeMemberForm.querySelector('input[name="discount_rate"]')||{}).value || '3.00';

    // Basic client-side validation
    if(!fd.first_name || !fd.last_name || !fd.email || !fd.phone || !fd.address || !fd.password || !fd.password_verify){
      themedSwal({ icon:'error', title:'Missing fields', text:'Please fill required fields.' });
      return;
    }
    if(fd.password !== fd.password_verify){
      themedSwal({ icon:'error', title:'Passwords do not match', text:'Please re-check password fields.' });
      return;
    }

    // hide make member modal
    const m = bootstrap.Modal.getInstance(document.getElementById('makeMemberModal'));
    if(m) m.hide();

    // queue action for global verify
    openGlobalAdminModal('create_new_member', fd);
  });
}

/* ---------- DELETE USER ---------- */
document.addEventListener("click", async e=>{
  const btn = e.target.closest(".delete-user-btn");
  if(!btn) return;

  const userId = btn.dataset.userId;
  const c = await themedSwal({
    icon:'warning', title:'Delete User',
    html:'This action cannot be undone.', confirmButtonText:'Delete'
  });
  if(!c.isConfirmed) return;

  openGlobalAdminModal('delete_user', { user_id: userId });
});

/* ---------- REMOVE MEMBERSHIP ---------- */
document.addEventListener("click", async e=>{
  const btn = e.target.closest(".remove-member-btn");
  if(!btn) return;

  const userId = btn.dataset.userId;
  const c = await themedSwal({ icon:'question', title:'Remove Membership', confirmButtonText:'Continue' });
  if(!c.isConfirmed) return;

  openGlobalAdminModal('remove_member', { user_id: userId });
});

/* ============================================================
   VIEW DETAILS — robust parser (handles direct JSON and base64 JSON)
   - membership_users/membership_members supply data-user (JSON encoded)
   - This handler tries parse directly; if it fails, it will attempt atob()
   ============================================================ */
document.addEventListener("click", e=>{
  const btn = e.target.closest(".view-detail-btn");
  if(!btn) return;

  const raw = btn.getAttribute('data-user') || '';

  let data = null;

  // Try direct JSON.parse first
  try {
    data = JSON.parse(raw);
  } catch (err) {
    // Try decode base64 then parse
    try {
      const decoded = atob(raw);
      data = JSON.parse(decoded);
    } catch (err2) {
      console.error('Failed to parse data-user payload', err2);
      themedSwal({ icon:'error', title:'Invalid data', text:'Unable to read user details.' });
      return;
    }
  }

  const html = `
    <p><strong>Name:</strong> ${data.name || ''}</p>
    <p><strong>Email:</strong> ${data.email || ''}</p>
    <p><strong>Phone:</strong> ${data.phone || ''}</p>
    <p><strong>Address:</strong> ${data.address || ''}</p>
    ${data.status ? `<p><strong>Status:</strong> ${data.status}</p>` : ''}
    ${data.start_date ? `<p><strong>Membership:</strong> ${data.start_date} — ${data.end_date || ''}</p>` : ''}
  `;

  const detailBody = document.getElementById('detailBody');
  if(detailBody) detailBody.innerHTML = html;
  new bootstrap.Modal(document.getElementById('detailModal')).show();
});
</script>

</body>
</html>
