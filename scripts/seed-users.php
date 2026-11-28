<?php

declare(strict_types=1);

// Dodaje konta demonstracyjne - po jednym dla każdej roli - przez UserRepository.
// Uruchomienie w terminalu WSL, w katalogu projektu:
//   docker compose exec php php scripts/seed-users.php
// Konto, którego e-mail już jest w bazie, zostaje pominięte, więc skrypt można uruchomić wiele razy.
// Hasło jest jawne celowo: to konta do demo, opisane w README.

use App\Database;
use App\Entity\User;
use App\Repository\UserRepository;

require_once dirname(__DIR__) . '/vendor/autoload.php';

const DEMO_PASSWORD = 'demo1234';

$repository = new UserRepository(new Database());

$accounts = [
    ['name' => 'Demo Starter', 'email' => 'starter@cookup.test', 'role' => User::ROLE_STARTER],
    ['name' => 'Demo Pro', 'email' => 'pro@cookup.test', 'role' => User::ROLE_PRO],
    ['name' => 'Demo Admin', 'email' => 'admin@cookup.test', 'role' => User::ROLE_ADMIN],
];

foreach ($accounts as $account) {
    $id = $repository->create($account['name'], $account['email'], password_hash(DEMO_PASSWORD, PASSWORD_DEFAULT), false);

    if ($id === null) {
        printf("Skipped %s - account already exists.\n", $account['email']);
        continue;
    }

    // nowe konto zawsze dostaje rolę starter (domyślna w bazie), pozostałe ustawiamy osobno
    if ($account['role'] !== User::ROLE_STARTER) {
        $repository->updateRole($id, $account['role']);
    }

    printf("Added %s (%s)\n", $account['email'], $account['role']);
}
