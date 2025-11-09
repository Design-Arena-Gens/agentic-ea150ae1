<?php
require_once __DIR__ . '/lib/auth.php';
require_once __DIR__ . '/lib/messages.php';
require_login();
$thread_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($thread_id <= 0) { http_response_code(400); echo 'Invalid thread'; exit; }
$thread = get_thread($thread_id);
if (!$thread) { http_response_code(404); echo 'Thread not found'; exit; }
$user_id = current_user_id();
if (!is_admin() && !is_user_participant($thread_id, $user_id)) {
    http_response_code(403); echo 'Forbidden'; exit;
}
$participants = get_thread_participants($thread_id);
$messages = get_thread_messages($thread_id);
mark_thread_read($thread_id, $user_id);
?>
<?php include __DIR__ . '/partials/header.php'; ?>
<?php include __DIR__ . '/partials/navbar.php'; ?>
<div class="container">
  <div class="row">
    <div class="col-lg-8">
      <div class="card shadow-sm mb-3">
        <div class="card-body">
          <h5 class="card-title mb-1"><?php echo e($thread['subject']); ?></h5>
          <div class="mb-2"><span class="badge bg-secondary"><?php echo e($thread['type']); ?></span> <span class="badge bg-<?php echo $thread['status']==='open'?'success':($thread['status']==='resolved'?'warning':'secondary'); ?> ms-2"><?php echo e($thread['status']); ?></span></div>
          <div class="border-top pt-3">
            <?php foreach ($messages as $m): $me = ((int)$m['sender_id'] === $user_id); ?>
              <div class="mb-3">
                <div class="small text-muted mb-1"><?php echo e($m['first_name'].' '.$m['last_name']); ?> (<?php echo e($m['role']); ?>) ? <?php echo e($m['created_at']); ?></div>
                <div class="message <?php echo $me ? 'me' : 'other'; ?>"><?php echo nl2br(e($m['body'])); ?></div>
              </div>
            <?php endforeach; ?>
          </div>
          <form method="post" action="<?php echo base_url('message_send.php'); ?>" class="mt-3">
            <input type="hidden" name="thread_id" value="<?php echo (int)$thread_id; ?>">
            <div class="mb-2">
              <textarea name="body" class="form-control" rows="3" placeholder="Write a reply..." required></textarea>
            </div>
            <div class="d-flex gap-2">
              <button class="btn btn-primary" type="submit">Send</button>
              <?php if (is_admin()): ?>
                <a class="btn btn-outline-secondary" href="<?php echo base_url('admin/add_participant.php?thread_id='.(int)$thread_id); ?>">Add Participant</a>
              <?php endif; ?>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="card shadow-sm mb-3">
        <div class="card-body">
          <h6 class="card-subtitle mb-2 text-muted">Participants</h6>
          <ul class="list-group">
            <?php foreach ($participants as $p): ?>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <span><?php echo e($p['first_name'].' '.$p['last_name']); ?> <small class="text-muted">(<?php echo e($p['role']); ?>)</small></span>
                <span class="badge bg-light text-dark"><?php echo e($p['role']); ?></span>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
      <?php if (is_admin()): ?>
      <div class="card shadow-sm">
        <div class="card-body">
          <form method="post" action="<?php echo base_url('admin/thread_status.php'); ?>" class="d-flex gap-2">
            <input type="hidden" name="thread_id" value="<?php echo (int)$thread_id; ?>">
            <select name="status" class="form-select">
              <option value="open" <?php echo $thread['status']==='open'?'selected':''; ?>>Open</option>
              <option value="resolved" <?php echo $thread['status']==='resolved'?'selected':''; ?>>Resolved</option>
              <option value="closed" <?php echo $thread['status']==='closed'?'selected':''; ?>>Closed</option>
            </select>
            <button class="btn btn-outline-primary" type="submit">Update</button>
          </form>
        </div>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>
<?php include __DIR__ . '/partials/footer.php'; ?>
