<?php
require_once __DIR__ . '/../lib/auth.php';
require_role('admin');
$role = $_GET['role'] ?? '';
$where = '';
if (in_array($role, ['student','teacher','staff','admin'], true)) { $where = "WHERE role='".mysqli_real_escape_string($mysqli, $role)."'"; }
$users = mysqli_query($mysqli, "SELECT u.*, d.name AS dept_name FROM users u LEFT JOIN departments d ON d.id = u.department_id $where ORDER BY role, first_name, last_name");
?>
<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/navbar.php'; ?>
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Users</h4>
    <a class="btn btn-primary" href="<?php echo base_url('admin/user_form.php'); ?>">Add User</a>
  </div>
  <div class="mb-2">
    <a class="btn btn-sm btn-outline-secondary me-1" href="<?php echo base_url('admin/users.php'); ?>">All</a>
    <?php foreach (['student','teacher','staff','admin'] as $r): ?>
      <a class="btn btn-sm btn-outline-secondary me-1" href="<?php echo base_url('admin/users.php?role='.$r); ?>"><?php echo ucfirst($r); ?></a>
    <?php endforeach; ?>
  </div>
  <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead><tr><th>#</th><th>Name</th><th>Role</th><th>Identifier</th><th>Department</th><th>Status</th><th></th></tr></thead>
      <tbody>
        <?php while ($u = mysqli_fetch_assoc($users)): ?>
          <tr>
            <td><?php echo (int)$u['id']; ?></td>
            <td><?php echo e($u['first_name'].' '.$u['last_name']); ?></td>
            <td><?php echo e($u['role']); ?></td>
            <td>
              <?php if ($u['role']==='student'): ?>Reg: <?php echo e($u['registration_no']); ?><?php else: ?>User: <?php echo e($u['username']); ?><?php endif; ?>
            </td>
            <td><?php echo e($u['dept_name'] ?? '?'); ?></td>
            <td><?php echo ((int)$u['is_active']===1)?'<span class="badge bg-success">Active</span>':'<span class="badge bg-secondary">Inactive</span>'; ?></td>
            <td class="text-end">
              <a class="btn btn-sm btn-outline-primary" href="<?php echo base_url('admin/user_form.php?id='.(int)$u['id']); ?>">Edit</a>
              <a class="btn btn-sm btn-outline-warning" href="<?php echo base_url('admin/toggle_user.php?id='.(int)$u['id']); ?>" onclick="return confirm('Toggle active status?')">Toggle</a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>
