<?php
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

require_auth();

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$remember_me = !empty($_POST['remember_me']);

if ($username === '' || $password === '') {
    set_flash('danger', 'Username and password are required.');
    header('Location: index.php');
    exit;
}

if (!preg_match('/^[A-Za-z0-9_.@-]{4,20}$/', $username)) {
    set_flash('danger', 'Username must be 4 to 20 characters and contain only letters, numbers, underscores, dots, or the @ symbol.');
    header('Location: index.php');
    exit;
}

$database = ensure_database();
$validUser = null;

foreach ($database['users'] as $user) {
    if (($user['username'] ?? '') === $username && password_verify($password, $user['password'])) {
        $validUser = $user;
        break;
    }
}

if ($validUser === null) {
    set_flash('danger', 'Invalid username or password.');
    header('Location: index.php');
    exit;
}

$_SESSION['auth'] = true;
$_SESSION['user'] = ['username' => $username];

if ($remember_me) {
    $token = bin2hex(random_bytes(32));
    remember_user($username, $token);
} else {
    clear_remember_user($username);
}

header('Location: register.php');
exit;
