<?php

declare(strict_types=1);

use App\Controller\DefaultController;
use App\Controller\LoopController;
use App\Controller\ProfileController;
use App\Controller\SecurityController;
use App\Database;
use App\Repository\LoopRepository;
use App\Routing;

require_once dirname(__DIR__) . '/vendor/autoload.php';

// Punkt składania aplikacji: tutaj tworzymy zależności i przekazujemy je kontrolerom,
// więc kontroler dostaje gotowe repozytorium i sam nie tworzy połączenia z bazą.
$loopRepository = new LoopRepository(new Database());

$routing = new Routing([
    DefaultController::class => static fn (): DefaultController => new DefaultController($loopRepository),
    LoopController::class => static fn (): LoopController => new LoopController($loopRepository),
    ProfileController::class => static fn (): ProfileController => new ProfileController($loopRepository),
]);

$routing->get('/', DefaultController::class, 'index');
$routing->get('/pricing', DefaultController::class, 'pricing');
$routing->get('/loops', LoopController::class, 'index');
$routing->get('/loops/{id}', LoopController::class, 'show');
$routing->get('/login', SecurityController::class, 'login');
$routing->get('/register', SecurityController::class, 'register');
$routing->get('/profile', ProfileController::class, 'index');

try {
    $routeFound = $routing->dispatch(
        $_SERVER['REQUEST_METHOD'],
        $_SERVER['REQUEST_URI'],
    );

    if (!$routeFound) {
        (new DefaultController($loopRepository))->notFound();
    }
} catch (Throwable $exception) {
    // szczegóły (host, nazwa bazy, ścieżki plików) tylko do logu serwera,
    // użytkownik dostaje ogólny komunikat i kod 500
    error_log((string) $exception);
    (new DefaultController($loopRepository))->serverError();
}
