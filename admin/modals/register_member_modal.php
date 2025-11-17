<div class="modal fade" id="registerMemberModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form class="modal-content" action="../functions/register_member.php" method="POST">
      <div class="modal-header">
        <h5 class="modal-title"><i class="bi bi-person-plus me-2"></i>Register New Member</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3 position-relative">
          <label class="form-label">Search User</label>
          <input type="text" id="userSearch" class="form-control" placeholder="Type user name..." autocomplete="off" required>
          <input type="hidden" name="user_id" id="userId">
          <ul id="userResults" class="list-group position-absolute w-100 mt-1" style="z-index:1000;"></ul>
        </div>

        <div class="mb-3">
          <label class="form-label">Discount Rate (%)</label>
          <input type="text" name="discount_rate" class="form-control" value="3.00" readonly>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary">Register</button>
      </div>
    </form>
  </div>
</div>

<script>
const userSearch = document.getElementById('userSearch');
const userResults = document.getElementById('userResults');
const userIdInput = document.getElementById('userId');

userSearch.addEventListener('input', async function() {
  const query = this.value.trim();
  if (!query) { userResults.innerHTML = ''; return; }
  const res = await fetch('../functions/search_users.php?q=' + encodeURIComponent(query));
  const data = await res.json();
  userResults.innerHTML = data.map(u =>
    `<li class="list-group-item list-group-item-action" data-id="${u.id}">${u.name}</li>`
  ).join('');
});
userResults.addEventListener('click', e => {
  if (e.target.matches('li')) {
    userSearch.value = e.target.textContent;
    userIdInput.value = e.target.dataset.id;
    userResults.innerHTML = '';
  }
});
</script>
