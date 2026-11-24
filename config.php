<?php

declare(strict_types=1);

// Dane połączenia z bazą. Wartości przychodzą ze zmiennych środowiskowych,
// które docker-compose.yaml przekazuje do kontenera PHP z pliku .env,
// więc hasło nie trafia do repozytorium.
define('DB_HOST', getenv('DB_HOST') ?: 'db');
define('DB_PORT', (int) (getenv('DB_PORT') ?: 5432));
define('DB_NAME', (string) getenv('DB_NAME'));
define('DB_USERNAME', (string) getenv('DB_USERNAME'));
define('DB_PASSWORD', (string) getenv('DB_PASSWORD'));
