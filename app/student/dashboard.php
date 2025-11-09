<?php
require_once __DIR__ . '/../lib/auth.php';
require_once __DIR__ . '/../lib/messages.php';
require_role('student');
$user_id = current_user_id();
$threads = get_threads_for_user($user_id);
?>
<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/navbar.php'; ?>
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Student Dashboard</h4>
    <a class="btn btn-primary" href="<?php echo base_url('student/new_thread.php'); ?>">New Inquiry/Claim</a>
  </div>
  <div class="list-group">
    <?php if (!$threads): ?>
      <div class="alert alert-info">No conversations yet. Start a new one.</div>
    <?php endif; ?>
    <?php foreach ($threads as $t): ?>
      <a class="list-group-item list-group-item-action" href="<?php echo base_url('thread.php?id='.(int)$t['id']); ?>">
        <div class="fw-semibold"><?php echo e($t['subject']); ?> <span class="badge bg-secondary ms-2"><?php echo e($t['type']); ?></span></div>
        <small class="text-muted">Status: <?php echo e($t['status']); ?> ? Updated: <?php echo e($t['updated_at']); ?></small>
      </a>
    <?php endforeach; ?>
  </div>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>
