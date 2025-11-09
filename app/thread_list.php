<?php
require_once __DIR__ . '/lib/auth.php';
require_once __DIR__ . '/lib/messages.php';
require_login();
$user_id = current_user_id();
$threads = get_threads_for_user($user_id);
?>
<?php include __DIR__ . '/partials/header.php'; ?>
<?php include __DIR__ . '/partials/navbar.php'; ?>
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Your Inbox</h4>
    <?php if (is_student()): ?>
      <a class="btn btn-sm btn-primary" href="<?php echo base_url('student/new_thread.php'); ?>">New Inquiry/Claim</a>
    <?php endif; ?>
  </div>
  <?php if (!$threads): ?>
    <div class="alert alert-info">No conversations yet.</div>
  <?php else: ?>
    <div class="list-group">
      <?php foreach ($threads as $t): ?>
        <?php $latest_id = get_latest_message_id((int)$t['id']);
              $stmt = mysqli_prepare($mysqli, "SELECT last_read_message_id FROM thread_reads WHERE thread_id = ? AND user_id = ?");
              mysqli_stmt_bind_param($stmt, 'ii', $t['id'], $user_id);
              mysqli_stmt_execute($stmt);
              $res = mysqli_stmt_get_result($stmt);
              $rr = mysqli_fetch_assoc($res);
              mysqli_stmt_close($stmt);
              $unread = ($latest_id > (int)($rr['last_read_message_id'] ?? 0));
        ?>
        <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" href="<?php echo base_url('thread.php?id='.(int)$t['id']); ?>">
          <div>
            <div class="fw-semibold"><?php echo e($t['subject']); ?> <span class="badge bg-secondary ms-2"><?php echo e($t['type']); ?></span></div>
            <small class="text-muted">Status: <?php echo e($t['status']); ?> ? Updated: <?php echo e($t['updated_at']); ?></small>
          </div>
          <?php if ($unread): ?><span class="badge bg-primary">New</span><?php endif; ?>
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
<?php include __DIR__ . '/partials/footer.php'; ?>
