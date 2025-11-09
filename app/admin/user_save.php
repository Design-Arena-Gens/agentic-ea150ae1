<?php
require_once __DIR__ . '/../lib/auth.php';
require_role('admin');

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$role = $_POST['role'] ?? '';
$first = trim($_POST['first_name'] ?? '');
$last = trim($_POST['last_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$dept = isset($_POST['department_id']) && $_POST['department_id'] !== '' ? (int)$_POST['department_id'] : null;
$reg = trim($_POST['registration_no'] ?? '');
$usern = trim($_POST['username'] ?? '');
$position = trim($_POST['position'] ?? '');
$pwd = $_POST['password'] ?? '';

if (!in_array($role, ['student','teacher','staff','admin'], true)) { http_response_code(400); echo 'Bad role'; exit; }
if ($first === '' || $last === '') { http_response_code(400); echo 'Name required'; exit; }

if ($id > 0) {
    // update
    $fields = ['first_name=?, last_name=?, email=?, department_id=?, position=?'];
    $params = [$first, $last, $email, $dept, $position];
    $types = 'sssis';
    if ($role === 'student') { $fields[] = 'registration_no=?'; $params[] = $reg; $types .= 's'; }
    else { $fields[] = 'username=?'; $params[] = $usern; $types .= 's'; }
    if ($pwd !== '') { $fields[] = 'password_hash=?'; $params[] = password_hash($pwd, PASSWORD_DEFAULT); $types .= 's'; }
    $params[] = $id; $types .= 'i';
    $sql = 'UPDATE users SET ' . implode(', ', $fields) . ' WHERE id = ?';
    $stmt = mysqli_prepare($mysqli, $sql);
    mysqli_stmt_bind_param($stmt, $types, ...$params);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
} else {
    // insert
    $pwd_hash = password_hash($pwd, PASSWORD_DEFAULT);
    if ($role === 'student') {
        $stmt = mysqli_prepare($mysqli, "INSERT INTO users (role, registration_no, password_hash, first_name, last_name, email, position, department_id, is_active, created_at) VALUES ('student', ?, ?, ?, ?, ?, ?, ?, 1, NOW())");
        mysqli_stmt_bind_param($stmt, 'ssssssi', $reg, $pwd_hash, $first, $last, $email, $position, $dept);
    } else {
        $stmt = mysqli_prepare($mysqli, "INSERT INTO users (role, username, password_hash, first_name, last_name, email, position, department_id, is_active, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1, NOW())");
        mysqli_stmt_bind_param($stmt, 'sssssssi', $role, $usern, $pwd_hash, $first, $last, $email, $position, $dept);
    }
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

header('Location: ' . base_url('admin/users.php'));
