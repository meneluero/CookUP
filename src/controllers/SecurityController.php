<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\UserRepository;

final class SecurityController extends AppController
{
    private const MIN_PASSWORD_LENGTH = 8;
    private const MAX_NAME_LENGTH = 120;
    private const MAX_EMAIL_LENGTH = 255;

    private const EXPIRED_FORM_ERROR = 'This form has expired. Refresh the page and try again.';
    // ten sam tekst pokazuje przeglądarka (public/js/forms.js) i serwer
    private const PASSWORD_MISMATCH_ERROR = 'Passwords do not match.';

    public function __construct(private readonly UserRepository $userRepository)
    {
    }

    public function login(): void
    {
        if ($this->currentUserId() !== null) {
            $this->redirect('/profile');
        }

        if (!$this->isPost()) {
            $this->renderForm('login', [
                'notice' => isset($_GET['registered']) ? 'Account created. You can sign in now.' : null,
            ]);
            return;
        }

        $email = mb_strtolower(trim((string) ($_POST['email'] ?? '')));
        $password = (string) ($_POST['password'] ?? '');

        if (!$this->isCsrfTokenValid((string) ($_POST['csrfToken'] ?? ''))) {
            http_response_code(403);
            $this->renderForm('login', ['errors' => [self::EXPIRED_FORM_ERROR], 'form' => ['email' => $email]]);
            return;
        }

        $user = $this->userRepository->findByEmail($email);

        // jeden ogólny komunikat - nie zdradzamy, czy konto z tym e-mailem istnieje
        if ($user === null || !password_verify($password, $user->getPasswordHash())) {
            $this->renderForm('login', ['errors' => ['Invalid email or password.'], 'form' => ['email' => $email]]);
            return;
        }

        // nowy identyfikator sesji po zalogowaniu chroni przed session fixation
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user->getId();
        $_SESSION['user_name'] = $user->getName();
        $_SESSION['logged_in_at'] = time();

        $this->redirect('/profile');
    }

    public function register(): void
    {
        if ($this->currentUserId() !== null) {
            $this->redirect('/profile');
        }

        if (!$this->isPost()) {
            $this->renderForm('register');
            return;
        }

        $name = trim((string) ($_POST['name'] ?? ''));
        $email = mb_strtolower(trim((string) ($_POST['email'] ?? '')));
        $password = (string) ($_POST['password'] ?? '');
        $confirmation = (string) ($_POST['passwordConfirmation'] ?? '');
        $newsletter = isset($_POST['newsletter']);

        // hasła nigdy nie wracają do formularza
        $form = ['name' => $name, 'email' => $email, 'newsletter' => $newsletter];

        if (!$this->isCsrfTokenValid((string) ($_POST['csrfToken'] ?? ''))) {
            http_response_code(403);
            $this->renderForm('register', ['errors' => [self::EXPIRED_FORM_ERROR], 'form' => $form]);
            return;
        }

        // walidacja po stronie serwera - atrybuty required/minlength w HTML można ominąć
        $errors = [];

        if ($name === '') {
            $errors[] = 'Enter your name.';
        } elseif (mb_strlen($name) > self::MAX_NAME_LENGTH) {
            $errors[] = 'Name can have at most ' . self::MAX_NAME_LENGTH . ' characters.';
        }

        $isEmailValid = filter_var($email, FILTER_VALIDATE_EMAIL) !== false
            && mb_strlen($email) <= self::MAX_EMAIL_LENGTH;

        if (!$isEmailValid) {
            $errors[] = 'Enter a valid email address.';
        }
        if (mb_strlen($password) < self::MIN_PASSWORD_LENGTH) {
            $errors[] = 'Password must have at least ' . self::MIN_PASSWORD_LENGTH . ' characters.';
        }
        if ($password !== $confirmation) {
            $errors[] = self::PASSWORD_MISMATCH_ERROR;
        }
        if ($isEmailValid && $this->userRepository->findByEmail($email) !== null) {
            $errors[] = 'An account with this email already exists.';
        }

        if ($errors === []) {
            $userId = $this->userRepository->create(
                $name,
                $email,
                password_hash($password, PASSWORD_DEFAULT),
                $newsletter,
            );

            // null = unikalny indeks w bazie odrzucił e-mail (równoległa rejestracja)
            if ($userId !== null) {
                $this->redirect('/login?registered=1');
            }

            $errors[] = 'An account with this email already exists.';
        }

        $this->renderForm('register', ['errors' => $errors, 'form' => $form]);
    }

    public function logout(): void
    {
        // wylogowanie zmienia stan, więc tylko POST z tokenem CSRF
        if (!$this->isCsrfTokenValid((string) ($_POST['csrfToken'] ?? ''))) {
            $this->renderError(403, 'Forbidden', self::EXPIRED_FORM_ERROR);
            return;
        }

        $this->destroySession();
        $this->redirect('/login');
    }

    private function renderForm(string $mode, array $variables = []): void
    {
        $this->render('login', $variables + [
            'pageTitle' => ($mode === 'register' ? 'Register' : 'Sign in') . ' | CookUP+',
            'mode' => $mode,
            'errors' => [],
            'notice' => null,
            'form' => [],
            'passwordMismatchError' => self::PASSWORD_MISMATCH_ERROR,
        ]);
    }
}
