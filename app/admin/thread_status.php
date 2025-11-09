<?php
require_once __DIR__ . '/../lib/auth.php';
require_role('admin');
$thread_id = (int)($_POST['thread_id'] ?? 0);
$status = $_POST['status'] ?? 'open';
if ($thread_id <= 0 || !in_array($status, ['open','resolved','closed'], true)) { http_response_code(400); echo 'Bad request'; exit; }
$now = date('Y-m-d H:i:s');
$stmt = mysqli_prepare($mysqli, "UPDATE threads SET status=?, updated_at=? WHERE id=?");
mysqli_stmt_bind_param($stmt, 'ssi', $status, $now, $thread_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
header('Location: ' . base_url('thread.php?id='.$thread_id));
