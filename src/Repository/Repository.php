<?php

declare(strict_types=1);

namespace App\Repository;

use App\Database;
use PDO;

// Bazowa klasa repozytoriów - wspólny dostęp do połączenia z bazą.
abstract class Repository
{
    public function __construct(private readonly Database $database)
    {
    }

    // połączenie otwiera się dopiero przy pierwszym zapytaniu,
    // więc strony bez danych z bazy (np. logowanie) w ogóle się nie łączą
    protected function connection(): PDO
    {
        return $this->database->connect();
    }
}
