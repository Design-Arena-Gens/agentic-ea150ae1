<?php
require_once __DIR__ . '/../lib/auth.php';
require_role('admin');
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) { http_response_code(400); echo 'Bad id'; exit; }
mysqli_query($mysqli, "UPDATE users SET is_active = 1 - is_active WHERE id = ".$id);
header('Location: ' . base_url('admin/users.php'));
