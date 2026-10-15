<?php

declare(strict_types=1);

use App\Controller\DefaultController;
use App\Controller\LoopController;
use App\Controller\ProfileController;
use App\Controller\SecurityController;
use App\Routing;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$routing = new Routing();

$routing->get('/', DefaultController::class, 'index');
$routing->get('/pricing', DefaultController::class, 'pricing');
$routing->get('/loops', LoopController::class, 'index');
$routing->get('/loops/{id}', LoopController::class, 'show');
$routing->get('/login', SecurityController::class, 'login');
$routing->get('/register', SecurityController::class, 'register');
$routing->get('/profile', ProfileController::class, 'index');

$routeFound = $routing->dispatch(
    $_SERVER['REQUEST_METHOD'],
    $_SERVER['REQUEST_URI'],
);

if (!$routeFound) {
    (new DefaultController())->notFound();
}
