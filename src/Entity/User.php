<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeImmutable;
use InvalidArgumentException;

/*
 Encja użytkownika - pola zgodne z tabelą users. Role trzymamy jako stałe klasy,
 żeby w kodzie nie wpisywać ręcznie napisów typu 'pro'.
*/
final readonly class User
{
    public const ROLE_STARTER = 'starter';
    public const ROLE_PRO = 'pro';
    public const ROLE_ADMIN = 'admin';

    public const ROLES = [self::ROLE_STARTER, self::ROLE_PRO, self::ROLE_ADMIN];

    public function __construct(
        private int $id,
        private string $name,
        private string $email,
        private string $passwordHash,
        private string $role,
        private bool $newsletter,
        private DateTimeImmutable $createdAt,
    ) {
        if (!in_array($role, self::ROLES, true)) {
            throw new InvalidArgumentException('Unknown role: ' . $role);
        }
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    // tylko do password_verify() przy logowaniu - nigdy nie wyświetlamy
    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function wantsNewsletter(): bool
    {
        return $this->newsletter;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
