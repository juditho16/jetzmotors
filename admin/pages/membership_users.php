<?php
// membership_users.php
if (!isset($users)) { die("Users dataset missing."); }
?>

<style>
.user-card-container { display:block; }
.user-card {
  border-radius:1rem;
  border:1px solid var(--color-base-300);
  background:var(--color-base-200);
  transition:transform .22s ease, box-shadow .28s ease;
  height:100%;
  display:flex;
  flex-direction:column;
  justify-content:space-between;
  padding:1rem !important;
}
.user-card:hover {
  transform:translateY(-6px);
  box-shadow:0 8px 20px rgba(0,0,0,0.08);
}
.user-avatar {
  width:70px; height:70px; border-radius:50%;
  background:var(--color-accent); color:white;
  font-weight:700; font-size:24px;
  display:flex; align-items:center; justify-content:center;
  margin:0 auto 10px auto;
}
.meta-small { font-size:.82rem; color:var(--color-secondary); }

.icon-btn {
  width:38px; height:38px; border-radius:50%;
  display:flex; align-items:center; justify-content:center;
  background:var(--color-primary);
  color:var(--color-primary-content);
  border:none;
  cursor:pointer;
  transition:.18s ease;
  box-shadow:0 4px 10px rgba(0,0,0,0.06);
}
.icon-btn:hover {
  background:transparent; color:var(--color-primary);
  border:2px solid var(--color-primary);
  transform:translateY(-3px);
}
</style>

<div class="row g-3" id="usersGrid">

<?php foreach ($users as $u):
  $name = sanitize($u['name']);
  $email = sanitize($u['email']);
  $phone = sanitize($u['phone']);
  $address = sanitize($u["address"] ?? "");
  $created = $u['created_at'] ?? '';

  // base64 JSON
  $encoded = base64_encode(json_encode([
      "id"=>$u["id"],
      "name"=>$name,
      "email"=>$email,
      "phone"=>$phone,
      "address"=>$address
  ]));
?>
  <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 user-card-container"
       data-name="<?= strtolower($name) ?>"
       data-created="<?= $created ?>">

    <div class="user-card">

      <div>
        <div class="user-avatar"><?= strtoupper(substr($name,0,2)) ?></div>

        <h6 class="text-center mb-1"><?= $name ?></h6>

        <div class="text-center meta-small mt-2">
          <div><strong>Email:</strong> <?= $email ?></div>
          <div><strong>Phone:</strong> <?= $phone ?></div>
        </div>
      </div>

      <div class="text-center mt-3 d-flex justify-content-center gap-2">
        
        <button class="icon-btn add-member-btn"
                data-user-id="<?= $u['id'] ?>"
                title="Add to Membership">
          <i class="bi bi-person-plus"></i>
        </button>

        <button class="icon-btn delete-user-btn"
                data-user-id="<?= $u['id'] ?>"
                title="Delete User">
          <i class="bi bi-trash"></i>
        </button>

        <button class="icon-btn view-detail-btn"
                data-user="<?= $encoded ?>"
                title="View Details">
          <i class="bi bi-eye"></i>
        </button>

      </div>

    </div>

  </div>
<?php endforeach; ?>

</div>
