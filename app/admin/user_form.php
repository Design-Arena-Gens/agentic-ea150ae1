<?php
require_once __DIR__ . '/../lib/auth.php';
require_role('admin');
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$editing = $id > 0;
$user = $editing ? get_user_by_id($id) : null;
$depts = mysqli_query($mysqli, "SELECT * FROM departments ORDER BY name");
?>
<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/navbar.php'; ?>
<div class="container" style="max-width: 720px;">
  <div class="card shadow-sm">
    <div class="card-body">
      <h5 class="card-title mb-3"><?php echo $editing ? 'Edit User' : 'Add User'; ?></h5>
      <form method="post" action="<?php echo base_url('admin/user_save.php'); ?>">
        <input type="hidden" name="id" value="<?php echo (int)($user['id'] ?? 0); ?>">
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Role</label>
            <select name="role" class="form-select" required <?php echo $editing?'disabled':''; ?>>
              <?php foreach (['student','teacher','staff','admin'] as $r): ?>
                <option value="<?php echo $r; ?>" <?php echo (($user['role'] ?? '')===$r)?'selected':''; ?>><?php echo ucfirst($r); ?></option>
              <?php endforeach; ?>
            </select>
            <?php if ($editing): ?><input type="hidden" name="role" value="<?php echo e($user['role']); ?>"><?php endif; ?>
          </div>
          <div class="col-md-4">
            <label class="form-label">First Name</label>
            <input type="text" name="first_name" class="form-control" value="<?php echo e($user['first_name'] ?? ''); ?>" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Last Name</label>
            <input type="text" name="last_name" class="form-control" value="<?php echo e($user['last_name'] ?? ''); ?>" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?php echo e($user['email'] ?? ''); ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">Department</label>
            <select name="department_id" class="form-select">
              <option value="">? None ?</option>
              <?php while ($d = mysqli_fetch_assoc($depts)): ?>
                <option value="<?php echo (int)$d['id']; ?>" <?php echo ((int)($user['department_id'] ?? 0)===(int)$d['id'])?'selected':''; ?>><?php echo e($d['name']); ?><?php echo $d['faculty']?(' ('.e($d['faculty']).')'):''; ?></option>
              <?php endwhile; ?>
            </select>
          </div>
          <div class="col-md-6 id-student" style="display: none;">
            <label class="form-label">Registration No (Students)</label>
            <input type="text" name="registration_no" class="form-control" value="<?php echo e($user['registration_no'] ?? ''); ?>">
          </div>
          <div class="col-md-6 id-nonstudent" style="display: none;">
            <label class="form-label">Username (Non-Students)</label>
            <input type="text" name="username" class="form-control" value="<?php echo e($user['username'] ?? ''); ?>">
          </div>
          <div class="col-md-6 id-staff" style="display: none;">
            <label class="form-label">Position (Staff)</label>
            <input type="text" name="position" class="form-control" value="<?php echo e($user['position'] ?? ''); ?>" placeholder="Dean, HoD, ...">
          </div>
          <div class="col-md-6">
            <label class="form-label"><?php echo $editing ? 'New Password (optional)' : 'Password'; ?></label>
            <input type="password" name="password" class="form-control" <?php echo $editing?'':'required'; ?>>
          </div>
        </div>
        <div class="mt-3 d-flex gap-2">
          <button class="btn btn-primary" type="submit">Save</button>
          <a class="btn btn-outline-secondary" href="<?php echo base_url('admin/users.php'); ?>">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
<script>
(function(){
  const roleSelect = document.querySelector('select[name=role]');
  function updateFields(){
    const role = roleSelect.value;
    document.querySelector('.id-student').style.display = role==='student'?'' : 'none';
    document.querySelector('.id-nonstudent').style.display = role==='student'?'none' : '';
    document.querySelector('.id-staff').style.display = role==='staff'?'' : 'none';
  }
  if (roleSelect) { roleSelect.addEventListener('change', updateFields); updateFields(); }
})();
</script>
<?php include __DIR__ . '/../partials/footer.php'; ?>
