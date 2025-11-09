<?php
require_once __DIR__ . '/lib/auth.php';
require_once __DIR__ . '/lib/messages.php';

if (!current_user_id()) {
    header('Location: ' . base_url('login.php'));
    exit;
}

$role = current_role();
if ($role === 'admin') {
    header('Location: ' . base_url('admin/dashboard.php'));
    exit;
}
if ($role === 'student') {
    header('Location: ' . base_url('student/dashboard.php'));
    exit;
}
if ($role === 'teacher') {
    header('Location: ' . base_url('teacher/dashboard.php'));
    exit;
}
if ($role === 'staff') {
    header('Location: ' . base_url('staff/dashboard.php'));
    exit;
}

http_response_code(400);
echo 'Unknown role.';
