<?php
require_once __DIR__ . '/lib/auth.php';
require_once __DIR__ . '/lib/messages.php';
require_login();
$thread_id = isset($_POST['thread_id']) ? (int)$_POST['thread_id'] : 0;
$body = trim($_POST['body'] ?? '');
if ($thread_id <= 0 || $body === '') { http_response_code(400); echo 'Bad request'; exit; }
if (!is_admin() && !is_user_participant($thread_id, current_user_id())) { http_response_code(403); echo 'Forbidden'; exit; }
$ok = add_message($thread_id, current_user_id(), $body);
if ($ok) {
    header('Location: ' . base_url('thread.php?id=' . $thread_id));
    exit;
}
http_response_code(500);
echo 'Failed to send message';
