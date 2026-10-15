<?php
declare(strict_types=1);

require_once __DIR__ . '/../model/user.php';
require_once __DIR__ . '/csrf.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params([
        'httponly' => true,
        'secure' => ($_SERVER['HTTPS'] ?? 'off') !== 'off',
        'samesite' => 'Lax',
    ]);
    session_start();
}

function currentUser(): ?array
{
    $user = $_SESSION['user'] ?? null;

    return is_array($user) && isset($user['id'], $user['username']) ? $user : null;
}

function loginUser(string $username, string $password): bool
{
    $user = getUserByUsername($username);
    $hash = $user['password_hash'] ?? password_hash(bin2hex(random_bytes(8)), PASSWORD_DEFAULT);

    if (!password_verify($password, $hash) || $user === null) {
        return false;
    }

    session_regenerate_id(true);
    $_SESSION['user'] = [
        'id' => (int) $user['id'],
        'username' => (string) $user['username'],
    ];

    return true;
}

function logoutUser(): void
{
    if (ini_get('session.use_cookies')) {
        $parameters = session_get_cookie_params();
        unset($parameters['lifetime']);
        setcookie(session_name(), '', ['expires' => time() - 42000] + $parameters);
    }

    $_SESSION = [];
    session_destroy();
}

function requireAuth(): void
{
    if (currentUser() === null) {
        header('Location: login.php?message=required');
        exit;
    }
}

function requireGuest(): void
{
    if (currentUser() !== null) {
        header('Location: product_list.php');
        exit;
    }
}