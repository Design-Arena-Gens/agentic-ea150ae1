<?php
require_once __DIR__ . '/../lib/auth.php';
require_role('student');
$teachers = mysqli_query($mysqli, "SELECT id, first_name, last_name FROM users WHERE role='teacher' AND is_active=1 ORDER BY first_name, last_name");
$staff = mysqli_query($mysqli, "SELECT id, first_name, last_name, position FROM users WHERE role='staff' AND is_active=1 ORDER BY first_name, last_name");
?>
<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/navbar.php'; ?>
<div class="container" style="max-width: 760px;">
  <div class="card shadow-sm">
    <div class="card-body">
      <h5 class="card-title mb-3">New Inquiry or Claim</h5>
      <form method="post" action="<?php echo base_url('student/create_thread.php'); ?>">
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Type</label>
            <select name="type" class="form-select" required>
              <option value="inquiry">Inquiry</option>
              <option value="claim">Claim</option>
            </select>
          </div>
          <div class="col-md-8">
            <label class="form-label">Subject</label>
            <input type="text" name="subject" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Tag Teachers (optional)</label>
            <select name="teachers[]" class="form-select" multiple size="5">
              <?php while ($t = mysqli_fetch_assoc($teachers)): ?>
                <option value="<?php echo (int)$t['id']; ?>"><?php echo e($t['first_name'].' '.$t['last_name']); ?></option>
              <?php endwhile; ?>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">Tag Office Staff (optional)</label>
            <select name="staff[]" class="form-select" multiple size="5">
              <?php while ($s = mysqli_fetch_assoc($staff)): ?>
                <option value="<?php echo (int)$s['id']; ?>"><?php echo e($s['first_name'].' '.$s['last_name']); ?><?php echo $s['position']?(' - '.e($s['position'])):''; ?></option>
              <?php endwhile; ?>
            </select>
          </div>
          <div class="col-12">
            <label class="form-label">Message</label>
            <textarea name="body" class="form-control" rows="5" required placeholder="Describe your inquiry or claim..."></textarea>
          </div>
        </div>
        <div class="mt-3 d-flex gap-2">
          <button class="btn btn-primary" type="submit">Create</button>
          <a class="btn btn-outline-secondary" href="<?php echo base_url('student/dashboard.php'); ?>">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>
