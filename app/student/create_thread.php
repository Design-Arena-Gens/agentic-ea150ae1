<?php
require_once __DIR__ . '/../lib/auth.php';
require_once __DIR__ . '/../lib/messages.php';
require_role('student');

$type = $_POST['type'] ?? 'inquiry';
$subject = trim($_POST['subject'] ?? '');
$body = trim($_POST['body'] ?? '');
$teachers = $_POST['teachers'] ?? [];
$staff = $_POST['staff'] ?? [];
$recipients = array_map('intval', array_filter(array_merge($teachers, $staff)));

if (!in_array($type, ['inquiry','claim'], true) || $subject === '' || $body === '') {
    http_response_code(400); echo 'Bad request'; exit;
}
$thread_id = create_thread(current_user_id(), $subject, $type, $body, $recipients);
if ($thread_id) {
    header('Location: ' . base_url('thread.php?id=' . $thread_id));
    exit;
}
http_response_code(500); echo 'Failed to create thread';
