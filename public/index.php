<?php

declare(strict_types=1);

use App\Controller\AdminController;
use App\Controller\DefaultController;
use App\Controller\LoopController;
use App\Controller\PricingController;
use App\Controller\ProfileController;
use App\Controller\SecurityController;
use App\Database;
use App\Repository\LoopRepository;
use App\Repository\UserRepository;
use App\Routing;

require_once dirname(__DIR__) . '/vendor/autoload.php';

// Sesja startuje w jednym miejscu, zanim cokolwiek trafi do przeglądarki.
// strict mode: PHP nie przyjmie identyfikatora sesji wymyślonego przez klienta
ini_set('session.use_strict_mode', '1');
// nocache = nagłówek Cache-Control: no-store na każdej stronie z sesją, więc po wylogowaniu
// przycisk Wstecz nie pokaże chronionej strony z pamięci przeglądarki (to wartość domyślna, ustawiamy ją jawnie)
session_cache_limiter('nocache');

$isHttps = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';

session_set_cookie_params([
    'lifetime' => 0,          // ciasteczko sesyjne - znika po zamknięciu przeglądarki
    'path' => '/',
    'secure' => $isHttps,     // tylko przez HTTPS, gdy strona działa na HTTPS
    'httponly' => true,       // JavaScript nie odczyta ciasteczka (utrudnia kradzież po XSS)
    'samesite' => 'Lax',      // obce strony nie dołączą ciasteczka do swoich żądań POST
]);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Punkt składania aplikacji: tutaj tworzymy zależności i przekazujemy je kontrolerom,
// więc kontroler dostaje gotowe repozytorium i sam nie tworzy połączenia z bazą.
$database = new Database();
$loopRepository = new LoopRepository($database);
$userRepository = new UserRepository($database);

$routing = new Routing([
    DefaultController::class => static fn (): DefaultController => new DefaultController($loopRepository),
    LoopController::class => static fn (): LoopController => new LoopController($loopRepository),
    ProfileController::class => static fn (): ProfileController => new ProfileController($loopRepository, $userRepository),
    SecurityController::class => static fn (): SecurityController => new SecurityController($userRepository),
    PricingController::class => static fn (): PricingController => new PricingController($userRepository),
    AdminController::class => static fn (): AdminController => new AdminController($userRepository, $loopRepository),
]);

// lista tras to biała lista operacji - nazwy kontrolera ani metody nie bierzemy z adresu URL
$routing->get('/', DefaultController::class, 'index');
$routing->get('/pricing', PricingController::class, 'index');
$routing->post('/pricing/upgrade', PricingController::class, 'upgrade');
$routing->get('/loops', LoopController::class, 'index');
$routing->get('/loops/{id}', LoopController::class, 'show');
$routing->get('/login', SecurityController::class, 'login');
$routing->post('/login', SecurityController::class, 'login');
$routing->get('/register', SecurityController::class, 'register');
$routing->post('/register', SecurityController::class, 'register');
$routing->post('/logout', SecurityController::class, 'logout');
$routing->get('/profile', ProfileController::class, 'index');
$routing->get('/admin', AdminController::class, 'index');
$routing->post('/admin/users/{id}/role', AdminController::class, 'changeRole');
$routing->post('/admin/users/{id}/delete', AdminController::class, 'deleteUser');
$routing->post('/admin/loops/{id}/delete', AdminController::class, 'deleteLoop');

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
