<?php
declare(strict_types=1);

require_once __DIR__ . '/../common/dbConnect.php';

function ensureUsersTableExists(): void
{
    static $initialized = false;

    if ($initialized) {
        return;
    }

    getDbConnection()->query(
        'CREATE TABLE IF NOT EXISTS users (
            id INT PRIMARY KEY AUTO_INCREMENT,
            username VARCHAR(50) NOT NULL UNIQUE,
            password_hash VARCHAR(255) NOT NULL,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        )'
    );
    $initialized = true;
}

function getUserByUsername(string $username): ?array
{
    ensureUsersTableExists();

    $statement = getDbConnection()->prepare(
        'SELECT id, username, password_hash FROM users WHERE username = ?'
    );
    $statement->execute([$username]);
    $user = $statement->get_result()->fetch_assoc();
    $statement->close();

    return $user ?: null;
}

function addUser(string $username, string $password): bool
{
    ensureUsersTableExists();

    $statement = getDbConnection()->prepare(
        'INSERT INTO users (username, password_hash) VALUES (?, ?)'
    );

    try {
        return $statement->execute([$username, password_hash($password, PASSWORD_DEFAULT)]);
    } catch (mysqli_sql_exception $exception) {
        if ($exception->getCode() === 1062) {
            return false;
        }

        throw $exception;
    } finally {
        $statement->close();
    }
}