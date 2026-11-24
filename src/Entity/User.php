<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeImmutable;
use InvalidArgumentException;

/*
 Encja użytkownika. Role trzymamy jako stałe klasy, żeby w kodzie
 nie wpisywać ręcznie napisów typu 'pro'. Email i hasło dojdą z logowaniem.
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
        private string $handle,
        private string $bio,
        private string $role,
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

    public function getHandle(): string
    {
        return $this->handle;
    }

    public function getBio(): string
    {
        return $this->bio;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
