<?php
session_start();

function database_path(): string
{
    return __DIR__ . '/database.json';
}

function ensure_database(): array
{
    $path = database_path();

    if (!file_exists($path) || filesize($path) === 0) {
        $initial = [
            'users' => [],
            'registrations' => [],
        ];
        file_put_contents($path, json_encode($initial, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    $contents = file_get_contents($path);
    $data = json_decode($contents, true);

    if (!is_array($data)) {
        $data = [
            'users' => [],
            'registrations' => [],
        ];
    }

    if (!isset($data['users']) || !is_array($data['users'])) {
        $data['users'] = [];
    }

    if (!isset($data['registrations']) || !is_array($data['registrations'])) {
        $data['registrations'] = [];
    }

    if (empty($data['users'])) {
        $data['users'][] = [
            'username' => 'admin',
            'password' => password_hash('Admin@123', PASSWORD_DEFAULT),
            'role' => 'admin',
            'created_at' => date('c'),
        ];
        file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    return $data;
}

function save_database(array $data): void
{
    file_put_contents(database_path(), json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}

function set_flash(string $type, string $message): void
{
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message,
    ];
}

function get_flash(): ?array
{
    if (!isset($_SESSION['flash'])) {
        return null;
    }

    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);

    return $flash;
}

function remember_user(string $username, string $token): void
{
    $db = ensure_database();

    foreach ($db['users'] as &$user) {
        if (($user['username'] ?? '') === $username) {
            $user['remember_token'] = password_hash($token, PASSWORD_DEFAULT);
            break;
        }
    }

    save_database($db);
    setcookie('secure_auth_remember', base64_encode($username . ':' . $token), time() + (60 * 60 * 24 * 30), '/');
}

function clear_remember_user(string $username): void
{
    $db = ensure_database();

    foreach ($db['users'] as &$user) {
        if (($user['username'] ?? '') === $username) {
            unset($user['remember_token']);
            break;
        }
    }

    save_database($db);
    setcookie('secure_auth_remember', '', time() - 3600, '/');
}

function is_authenticated(): bool
{
    if (!empty($_SESSION['auth']) && !empty($_SESSION['user']['username'])) {
        return true;
    }

    if (!empty($_COOKIE['secure_auth_remember'])) {
        $cookieValue = base64_decode($_COOKIE['secure_auth_remember'], true);

        if ($cookieValue !== false) {
            $parts = explode(':', $cookieValue, 2);

            if (count($parts) === 2) {
                [$username, $token] = $parts;
                $database = ensure_database();

                foreach ($database['users'] as $user) {
                    if (($user['username'] ?? '') === $username
                        && !empty($user['remember_token'])
                        && password_verify($token, $user['remember_token'])) {
                        $_SESSION['auth'] = true;
                        $_SESSION['user'] = ['username' => $username];
                        return true;
                    }
                }
            }
        }
    }

    return false;
}

function require_auth(): void
{
    if (!is_authenticated()) {
        set_flash('danger', 'You must log in before accessing the secure area.');
        header('Location: index.php');
        exit;
    }
}

function sanitize_text(string $value): string
{
    return trim(htmlspecialchars($value, ENT_QUOTES, 'UTF-8'));
}
