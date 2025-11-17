<?php
// membership_members.php
if (!isset($members)) { die("Members dataset missing."); }
?>

<style>
.member-card-container { display:block; }
.member-card {
  border-radius:1rem; border:1px solid var(--color-base-300);
  background:var(--color-base-200);
  transition:transform .22s ease, box-shadow .28s ease;
  height:100%;
  display:flex; flex-direction:column;
  justify-content:space-between;
  padding:1rem !important;
}
.member-card:hover {
  transform:translateY(-6px);
  box-shadow:0 8px 20px rgba(0,0,0,0.08);
}
.member-avatar {
  width:70px; height:70px; border-radius:50%;
  background:var(--color-accent); color:white;
  font-weight:700; font-size:24px;
  display:flex; align-items:center; justify-content:center;
  margin:0 auto 10px auto;
}
.badge-member {
  background:var(--color-primary);
  color:var(--color-primary-content);
  padding:.25rem .6rem;
  border-radius:20px;
  font-size:.75rem;
}
.meta-small { font-size:.82rem; color:var(--color-secondary); }

.icon-btn {
  width:38px; height:38px; border-radius:50%;
  display:flex; align-items:center; justify-content:center;
  background:var(--color-primary);
  color:var(--color-primary-content);
  border:none; cursor:pointer;
  transition:.18s ease;
  box-shadow:0 4px 10px rgba(0,0,0,0.08);
}
.icon-btn:hover {
  background:transparent; color:var(--color-primary);
  border:2px solid var(--color-primary);
  transform:translateY(-3px);
}
</style>

<div class="row g-3" id="membersGrid">

<?php foreach ($members as $m):

  $name = sanitize($m['name']);
  $email = sanitize($m['email']);
  $phone = sanitize($m['phone']);
  $address = sanitize($m['address']);
  $status = $m['status'];
  $discount = number_format($m["discount_rate"] ?? 3,2) . "%";

  $start_date = $m["start_date"] ? date("M d, Y", strtotime($m["start_date"])) : "N/A";
  $end_date   = $m["end_date"]   ? date("M d, Y", strtotime($m["end_date"]))   : "N/A";

  $created = $m["membership_created"] ?? $m["created_at"];

  // base64 JSON
  $encoded = base64_encode(json_encode([
    "id"=>$m["id"],
    "name"=>$name,
    "email"=>$email,
    "phone"=>$phone,
    "address"=>$address,
    "status"=>$status,
    "start_date"=>$m["start_date"],
    "end_date"=>$m["end_date"]
  ]));

?>
  <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 member-card-container"
       data-name="<?= strtolower($name) ?>"
       data-created="<?= $created ?>">

    <div class="member-card">

      <div>
        <div class="member-avatar"><?= strtoupper(substr($name,0,2)) ?></div>

        <h6 class="text-center mb-1"><?= $name ?></h6>

        <div class="text-center meta-small mt-2">
          <div><span class="badge-member"><?= $status ?></span></div>
          <div class="mt-1"><?= $start_date ?> — <?= $end_date ?></div>
          <div><small>Discount:</small> <?= $discount ?></div>
          <div><small>ID:</small> <?= $m["membership_id"] ?></div>
        </div>
      </div>

      <div class="text-center mt-3 d-flex justify-content-center gap-2">

        <button class="icon-btn remove-member-btn"
                data-user-id="<?= $m['id'] ?>"
                title="Remove Membership">
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
