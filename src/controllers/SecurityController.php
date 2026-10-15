<?php

declare(strict_types=1);

namespace App\Controller;

final class SecurityController extends AppController
{
    public function login(): void
    {
        $this->render('login', [
            'pageTitle' => 'Sign in | CookUP+',
            'mode' => 'login',
        ]);
    }

    public function register(): void
    {
        $this->render('login', [
            'pageTitle' => 'Register | CookUP+',
            'mode' => 'register',
        ]);
    }
}
