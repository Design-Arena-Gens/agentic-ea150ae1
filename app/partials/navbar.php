<?php require_once __DIR__ . '/../lib/auth.php'; ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?php echo base_url('index.php'); ?>"><?php echo e(APP_NAME); ?></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <?php if (current_user_id()): ?>
          <li class="nav-item"><a class="nav-link" href="<?php echo base_url('index.php'); ?>">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="<?php echo base_url('thread_list.php'); ?>">Inbox</a></li>
          <?php if (is_student()): ?>
            <li class="nav-item"><a class="nav-link" href="<?php echo base_url('student/new_thread.php'); ?>">New Inquiry/Claim</a></li>
          <?php endif; ?>
          <?php if (is_admin()): ?>
            <li class="nav-item"><a class="nav-link" href="<?php echo base_url('admin/users.php'); ?>">Users</a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo base_url('admin/departments.php'); ?>">Departments</a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo base_url('admin/threads.php'); ?>">All Threads</a></li>
          <?php endif; ?>
        <?php endif; ?>
      </ul>
      <ul class="navbar-nav">
        <?php if (current_user_id()): ?>
          <li class="nav-item"><span class="navbar-text me-3">Hello, <?php echo e($_SESSION['full_name'] ?? ''); ?> (<?php echo e(current_role()); ?>)</span></li>
          <li class="nav-item"><a class="nav-link" href="<?php echo base_url('logout.php'); ?>">Logout</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="<?php echo base_url('login.php'); ?>">Login</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
