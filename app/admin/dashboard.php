<?php
require_once __DIR__ . '/../lib/auth.php';
require_once __DIR__ . '/../lib/messages.php';
require_role('admin');

function count_rows($sql) { global $mysqli; $res = mysqli_query($mysqli, $sql); $row = mysqli_fetch_row($res); return (int)($row[0] ?? 0); }
$cnt_students = count_rows("SELECT COUNT(*) FROM users WHERE role='student'");
$cnt_teachers = count_rows("SELECT COUNT(*) FROM users WHERE role='teacher'");
$cnt_staff = count_rows("SELECT COUNT(*) FROM users WHERE role='staff'");
$cnt_threads_open = count_rows("SELECT COUNT(*) FROM threads WHERE status='open'");
$cnt_threads_total = count_rows("SELECT COUNT(*) FROM threads");
?>
<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/navbar.php'; ?>
<div class="container">
  <div class="row g-3 mb-4">
    <div class="col-md-3"><div class="card shadow-sm"><div class="card-body"><div class="text-muted">Students</div><div class="display-6"><?php echo $cnt_students; ?></div></div></div></div>
    <div class="col-md-3"><div class="card shadow-sm"><div class="card-body"><div class="text-muted">Teachers</div><div class="display-6"><?php echo $cnt_teachers; ?></div></div></div></div>
    <div class="col-md-3"><div class="card shadow-sm"><div class="card-body"><div class="text-muted">Staff</div><div class="display-6"><?php echo $cnt_staff; ?></div></div></div></div>
    <div class="col-md-3"><div class="card shadow-sm"><div class="card-body"><div class="text-muted">Open Threads</div><div class="display-6"><?php echo $cnt_threads_open; ?></div></div></div></div>
  </div>
  <div class="d-flex justify-content-between align-items-center mb-2">
    <h5>Recent Threads</h5>
    <a class="btn btn-sm btn-outline-secondary" href="<?php echo base_url('admin/threads.php'); ?>">View All</a>
  </div>
  <div class="list-group">
    <?php $res = mysqli_query($mysqli, "SELECT * FROM threads ORDER BY updated_at DESC LIMIT 10");
    while ($t = mysqli_fetch_assoc($res)): ?>
      <a class="list-group-item list-group-item-action" href="<?php echo base_url('thread.php?id='.(int)$t['id']); ?>">
        <div class="fw-semibold"><?php echo e($t['subject']); ?> <span class="badge bg-secondary ms-2"><?php echo e($t['type']); ?></span></div>
        <small class="text-muted">Status: <?php echo e($t['status']); ?> ? Updated: <?php echo e($t['updated_at']); ?></small>
      </a>
    <?php endwhile; ?>
  </div>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>
