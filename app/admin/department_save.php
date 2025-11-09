<?php
require_once __DIR__ . '/../lib/auth.php';
require_role('admin');
$name = trim($_POST['name'] ?? '');
$faculty = trim($_POST['faculty'] ?? '');
if ($name === '') { header('Location: ' . base_url('admin/departments.php')); exit; }
$stmt = mysqli_prepare($mysqli, "INSERT INTO departments (name, faculty) VALUES (?, ?)");
mysqli_stmt_bind_param($stmt, 'ss', $name, $faculty);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
header('Location: ' . base_url('admin/departments.php'));
