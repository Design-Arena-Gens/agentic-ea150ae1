<?php
require_once __DIR__ . '/../lib/auth.php';
require_role('admin');
$res = mysqli_query($mysqli, "SELECT t.*, u.first_name, u.last_name FROM threads t LEFT JOIN users u ON u.id = t.created_by ORDER BY updated_at DESC");
?>
<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/navbar.php'; ?>
<div class="container">
  <h4 class="mb-3">All Threads</h4>
  <div class="list-group">
    <?php while ($t = mysqli_fetch_assoc($res)): ?>
      <a class="list-group-item list-group-item-action" href="<?php echo base_url('thread.php?id='.(int)$t['id']); ?>">
        <div class="fw-semibold"><?php echo e($t['subject']); ?> <span class="badge bg-secondary ms-2"><?php echo e($t['type']); ?></span></div>
        <small class="text-muted">By: <?php echo e(($t['first_name']??'').' '.($t['last_name']??'')); ?> ? Status: <?php echo e($t['status']); ?> ? Updated: <?php echo e($t['updated_at']); ?></small>
      </a>
    <?php endwhile; ?>
  </div>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>
