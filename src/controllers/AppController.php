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

        extract($variables, EXTR_SKIP);
        require $templatePath;
    }

    protected function renderNotFound(): void
    {
        $this->renderError(404, 'Page not found', 'This page does not exist.');
    }

    // wspólna strona błędu (404, 500, a później np. 403)
    protected function renderError(int $statusCode, string $title, string $message): void
    {
        http_response_code($statusCode);

        $this->render('error', [
            'pageTitle' => $title . ' | CookUP+',
            'statusCode' => $statusCode,
            'message' => $message,
        ]);
    }
}
