<?php
require_once __DIR__ . '/../lib/auth.php';
require_role('admin');
$depts = mysqli_query($mysqli, "SELECT * FROM departments ORDER BY name");
?>
<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/navbar.php'; ?>
<div class="container" style="max-width: 720px;">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Departments</h4>
  </div>
  <form class="card shadow-sm mb-4" method="post" action="<?php echo base_url('admin/department_save.php'); ?>">
    <div class="card-body row g-3">
      <div class="col-md-5">
        <input type="text" name="name" placeholder="Department name" class="form-control" required>
      </div>
      <div class="col-md-5">
        <input type="text" name="faculty" placeholder="Faculty (optional)" class="form-control">
      </div>
      <div class="col-md-2 d-grid">
        <button class="btn btn-primary" type="submit">Add</button>
      </div>
    </div>
  </form>
  <div class="table-responsive">
    <table class="table table-striped">
      <thead><tr><th>#</th><th>Name</th><th>Faculty</th></tr></thead>
      <tbody>
        <?php while ($d = mysqli_fetch_assoc($depts)): ?>
          <tr><td><?php echo (int)$d['id']; ?></td><td><?php echo e($d['name']); ?></td><td><?php echo e($d['faculty']); ?></td></tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>
