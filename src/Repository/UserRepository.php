<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use DateTimeImmutable;
use PDO;
use PDOException;

// Cały SQL dotyczący użytkowników. Dane wychodzą stąd jako encje User.
final class UserRepository extends Repository
{
    private const COLUMNS = 'id, name, email, password_hash, role, newsletter, created_at';

    // kod SQLSTATE naruszenia unikalnego indeksu (tu: users_email_unique)
    private const UNIQUE_VIOLATION = '23505';

    public function find(int $id): ?User
    {
        $statement = $this->connection()->prepare(
            'SELECT ' . self::COLUMNS . ' FROM users WHERE id = :id'
        );
        $statement->execute(['id' => $id]);

        $row = $statement->fetch();

        return $row === false ? null : $this->mapRowToUser($row);
    }

    public function findByEmail(string $email): ?User
    {
        $statement = $this->connection()->prepare(
            'SELECT ' . self::COLUMNS . ' FROM users WHERE LOWER(email) = LOWER(:email) LIMIT 1'
        );
        $statement->execute(['email' => $email]);

        $row = $statement->fetch();

        return $row === false ? null : $this->mapRowToUser($row);
    }

    /**
     * Zwraca id nowego konta albo null, gdy e-mail jest już zajęty
     * (np. dwa równoległe żądania przeszły wcześniejsze sprawdzenie).
     */
    public function create(string $name, string $email, string $passwordHash, bool $newsletter): ?int
    {
        $statement = $this->connection()->prepare(
            'INSERT INTO users (name, email, password_hash, newsletter)
             VALUES (:name, :email, :password_hash, :newsletter)
             RETURNING id'
        );
        $statement->bindValue('name', $name);
        $statement->bindValue('email', $email);
        $statement->bindValue('password_hash', $passwordHash);
        $statement->bindValue('newsletter', $newsletter, PDO::PARAM_BOOL);

        try {
            $statement->execute();
        } catch (PDOException $exception) {
            if ($exception->getCode() === self::UNIQUE_VIOLATION) {
                return null;
            }

            throw $exception;
        }

        return (int) $statement->fetchColumn();
    }

    /**
     * @param array<string, mixed> $row
     */
    private function mapRowToUser(array $row): User
    {
        return new User(
            id: (int) $row['id'],
            name: (string) $row['name'],
            email: (string) $row['email'],
            passwordHash: (string) $row['password_hash'],
            role: (string) $row['role'],
            newsletter: (bool) $row['newsletter'],
            createdAt: new DateTimeImmutable((string) $row['created_at']),
        );
    }
}
