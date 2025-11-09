<?php
require_once __DIR__ . '/lib/auth.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = $_POST['role'] ?? '';
    $identifier = trim($_POST['identifier'] ?? '');
    $password = $_POST['password'] ?? '';
    $res = login($identifier, $password, $role);
    if ($res === true) {
        header('Location: ' . base_url('index.php'));
        exit;
    } else {
        $error = $res;
    }
}
?>
<?php include __DIR__ . '/partials/header.php'; ?>
<?php include __DIR__ . '/partials/navbar.php'; ?>
<div class="container" style="max-width: 480px;">
  <div class="card shadow-sm">
    <div class="card-body">
      <h5 class="card-title mb-3">Login</h5>
      <?php if ($error): ?><div class="alert alert-danger"><?php echo e($error); ?></div><?php endif; ?>
      <form method="post">
        <div class="mb-3">
          <label class="form-label">Login as</label>
          <select name="role" class="form-select" required>
            <option value="admin">Admin</option>
            <option value="student">Student</option>
            <option value="teacher">Teacher</option>
            <option value="staff">Office Staff</option>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Identifier</label>
          <input type="text" name="identifier" class="form-control" placeholder="Username or Registration No" required>
          <div class="form-text">Students: Registration No. Others: Username.</div>
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" required>
        </div>
        <button class="btn btn-primary w-100" type="submit">Login</button>
      </form>
    </div>
  </div>
</div>
<?php include __DIR__ . '/partials/footer.php'; ?>
