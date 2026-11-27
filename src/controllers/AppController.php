<?php

declare(strict_types=1);

namespace App\Controller;

abstract class AppController
{
    protected function render(string $template, array $variables = []): void
    {
        $templatePath = dirname(__DIR__, 2) . '/public/views/' . $template . '.html.php';

        if (!is_file($templatePath)) {
            throw new \RuntimeException('View not found: ' . $template);
        }

        // dane wspólne dla każdego widoku: token CSRF (formularz wylogowania w nagłówku)
        // i imię zalogowanego użytkownika
        $variables += [
            'csrfToken' => $this->csrfToken(),
            'currentUserName' => $_SESSION['user_name'] ?? null,
        ];

        extract($variables, EXTR_SKIP);
        require $templatePath;
    }

    protected function renderNotFound(): void
    {
        $this->renderError(404, 'Page not found', 'This page does not exist.');
    }

    // wspólna strona błędu (403, 404, 500)
    protected function renderError(int $statusCode, string $title, string $message): void
    {
        http_response_code($statusCode);

        $this->render('error', [
            'pageTitle' => $title . ' | CookUP+',
            'statusCode' => $statusCode,
            'message' => $message,
        ]);
    }

    protected function isPost(): bool
    {
        return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
    }

    // 303 See Other - po POST przeglądarka przechodzi na GET (wzorzec Post/Redirect/Get),
    // więc odświeżenie strony nie wyśle formularza ponownie
    protected function redirect(string $path): never
    {
        header('Location: ' . $path, true, 303);
        exit;
    }

    protected function currentUserId(): ?int
    {
        return isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;
    }

    // ochrona strony po stronie serwera - samo ukrycie linku w menu nie wystarcza
    protected function requireLogin(): int
    {
        $userId = $this->currentUserId();

        if ($userId === null) {
            $this->redirect('/login');
        }

        return $userId;
    }

    protected function csrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    protected function isCsrfTokenValid(string $token): bool
    {
        // hash_equals porównuje w stałym czasie, więc czas odpowiedzi nie zdradza tokenu
        return isset($_SESSION['csrf_token'])
            && hash_equals($_SESSION['csrf_token'], $token);
    }

    // czyści dane sesji, ciasteczko w przeglądarce i sesję na serwerze
    protected function destroySession(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', [
                'expires' => time() - 42000,
                'path' => $params['path'],
                'domain' => $params['domain'],
                'secure' => $params['secure'],
                'httponly' => $params['httponly'],
                'samesite' => $params['samesite'] ?? 'Lax',
            ]);
        }

        session_destroy();
    }
}
