<?php

declare(strict_types=1);

namespace App;

use PDO;
use PDOException;
use RuntimeException;

require_once dirname(__DIR__) . '/config.php';

final class Database
{
    private string $username;
    private string $password;
    private string $host;
    private int $port;
    private string $database;

    // jedno połączenie na całe żądanie, otwierane dopiero przy pierwszym zapytaniu
    private ?PDO $connection = null;

    public function __construct()
    {
        $this->username = DB_USERNAME;
        $this->password = DB_PASSWORD;
        $this->host = DB_HOST;
        $this->port = DB_PORT;
        $this->database = DB_NAME;
    }

    public function connect(): PDO
    {
        if ($this->connection !== null) {
            return $this->connection;
        }

        $dsn = sprintf(
            'pgsql:host=%s;port=%d;dbname=%s;sslmode=prefer',
            $this->host,
            $this->port,
            $this->database,
        );

        try {
            $this->connection = new PDO($dsn, $this->username, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                // natywne prepared statements - dane nigdy nie stają się częścią SQL
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $exception) {
            // ogólny komunikat - szczegóły sterownika mogą ujawnić host lub nazwę bazy
            throw new RuntimeException('Could not connect to the database.', 0, $exception);
        }

        return $this->connection;
    }
}
