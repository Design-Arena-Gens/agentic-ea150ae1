<?php
// Front controller to run PHP app on Vercel as a single Serverless Function
// Routes requests to the appropriate PHP file within the repository.

$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$uri = preg_replace('#/+#', '/', $uri);

$root = dirname(__DIR__);
$appRoot = $root . '/app';

function try_file($root, $rel) {
    $path = $root . '/' . ltrim($rel, '/');
    if (is_file($path) && substr($path, -4) === '.php') {
        return $path;
    }
    return null;
}

$target = null;

// Allow direct .php paths
if (substr($uri, -4) === '.php') {
    $target = try_file($root, $uri);
}

if (!$target) {
    // Try mapping pretty paths to php files
    $candidates = [];
    if ($uri === '/' || $uri === '') {
        $candidates[] = '/index.php';
    } else {
        $candidates[] = $uri . '.php';
        if (substr($uri, -1) === '/') $candidates[] = $uri . 'index.php';
    }

    foreach ($candidates as $cand) {
        $file = try_file($appRoot, $cand);
        if ($file) { $target = $file; break; }
    }
}

// Security: restrict to allowed directories
$allowedPrefixes = [
    $appRoot . '/index.php',
    $appRoot . '/login.php',
    $appRoot . '/logout.php',
    $appRoot . '/thread.php',
    $appRoot . '/thread_list.php',
    $appRoot . '/message_send.php',
    $appRoot . '/admin/',
    $appRoot . '/student/',
    $appRoot . '/teacher/',
    $appRoot . '/staff/'
];

$allowed = false;
if ($target) {
    foreach ($allowedPrefixes as $p) {
        if ($p === $target || str_starts_with($target, rtrim($p, '/').'/')) { $allowed = true; break; }
    }
}

if (!$target || !$allowed) {
    http_response_code(404);
    echo 'Not Found';
    exit;
}

require $target;
