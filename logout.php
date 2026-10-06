<?php
require_once __DIR__ . '/config.php';
require_auth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: register.php');
    exit;
}

$fields = [
    'first_name' => ['label' => 'First Name', 'pattern' => '/^[A-Za-zÀ-ÿ\s\'-]{2,50}$/'],
    'middle_name' => ['label' => 'Middle Name', 'pattern' => '/^[A-Za-zÀ-ÿ\s\'-]{0,50}$/'],
    'last_name' => ['label' => 'Last Name', 'pattern' => '/^[A-Za-zÀ-ÿ\s\'-]{2,50}$/'],
    'contact_number' => ['label' => 'Contact Number', 'pattern' => '/^09\d{9}$/'],
    'email' => ['label' => 'Email Address', 'pattern' => '/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/'],
    'website_url' => ['label' => 'Website URL', 'pattern' => '/^https?:\/\/(?:www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b(?:[-a-zA-Z0-9()@:%_\+.~#?&\/\/=]*)$/'],
];

$errors = [];
$record = [];

foreach ($fields as $key => $config) {
    $value = trim((string)($_POST[$key] ?? ''));

    if ($key === 'middle_name' && $value === '') {
        $record[$key] = '';
        continue;
    }

    if ($value === '') {
        $errors[] = $config['label'] . ' is required.';
        continue;
    }

    if (!preg_match($config['pattern'], $value)) {
        $errors[] = $config['label'] . ' is invalid.';
        continue;
    }

    $record[$key] = $value;
}

if (!empty($errors)) {
    set_flash('danger', implode('<br>', $errors));
    header('Location: register.php');
    exit;
}

$database = ensure_database();
$database['registrations'][] = [
    'first_name' => $record['first_name'],
    'middle_name' => $record['middle_name'] ?? '',
    'last_name' => $record['last_name'],
    'contact_number' => $record['contact_number'],
    'email' => $record['email'],
    'website_url' => $record['website_url'],
    'submitted_by' => $_SESSION['user']['username'] ?? 'Unknown',
    'created_at' => date('c'),
];

save_database($database);
set_flash('success', 'Registration submitted successfully.');
header('Location: register.php');
exit;
