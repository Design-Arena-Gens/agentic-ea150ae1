<?php
require_once __DIR__ . '/../lib/auth.php';
require_once __DIR__ . '/../lib/messages.php';
require_role('admin');
$thread_id = isset($_GET['thread_id']) ? (int)$_GET['thread_id'] : (int)($_POST['thread_id'] ?? 0);
if ($thread_id <= 0) { http_response_code(400); echo 'Bad thread'; exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = (int)($_POST['user_id'] ?? 0);
    if ($user_id > 0) { add_participant($thread_id, $user_id, 'participant'); }
    header('Location: ' . base_url('thread.php?id='.$thread_id));
    exit;
}
$users = mysqli_query($mysqli, "SELECT id, first_name, last_name, role FROM users WHERE is_active=1 ORDER BY role, first_name, last_name");
?>
<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/navbar.php'; ?>
<div class="container" style="max-width: 520px;">
  <div class="card shadow-sm">
    <div class="card-body">
      <h5 class="card-title mb-3">Add Participant</h5>
      <form method="post">
        <input type="hidden" name="thread_id" value="<?php echo (int)$thread_id; ?>">
        <div class="mb-3">
          <label class="form-label">Select user</label>
          <select name="user_id" class="form-select" required>
            <?php while ($u = mysqli_fetch_assoc($users)): ?>
              <option value="<?php echo (int)$u['id']; ?>"><?php echo e($u['first_name'].' '.$u['last_name']); ?> (<?php echo e($u['role']); ?>)</option>
            <?php endwhile; ?>
          </select>
        </div>
        <div class="d-flex gap-2">
          <button class="btn btn-primary" type="submit">Add</button>
          <a class="btn btn-outline-secondary" href="<?php echo base_url('thread.php?id='.(int)$thread_id); ?>">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>
