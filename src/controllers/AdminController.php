<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Repository\LoopRepository;
use App\Repository\UserRepository;

// Panel admina. Każda akcja zaczyna się od requireRole(), więc ręczne wywołanie
// adresu przez kogoś innego niż admin kończy się 403 (gość trafia na logowanie).
final class AdminController extends AppController
{
    // komunikat po akcji - w adresie jest tylko krótki klucz, tekst wybieramy tutaj
    private const NOTICES = [
        'role' => 'Role updated.',
        'user-deleted' => 'User deleted.',
        'loop-deleted' => 'Loop deleted.',
    ];

    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly LoopRepository $loopRepository,
    ) {
    }

    public function index(): void
    {
        $admin = $this->requireRole($this->userRepository, User::ROLE_ADMIN);
        $done = $_GET['done'] ?? '';

        $this->render('admin', [
            'pageTitle' => 'Admin panel | CookUP+',
            'activeNav' => '',
            'adminId' => $admin->getId(),
            'users' => $this->userRepository->findAll(),
            'loops' => $this->loopRepository->findAll(),
            'roles' => User::ROLES,
            'notice' => is_string($done) ? (self::NOTICES[$done] ?? null) : null,
        ]);
    }

    public function changeRole(int $id): void
    {
        $user = $this->findOtherUser($id);
        $role = (string) ($_POST['role'] ?? '');

        if (!in_array($role, User::ROLES, true)) {
            $this->renderError(400, 'Bad request', 'Unknown role.');
            return;
        }

        $this->userRepository->updateRole($user->getId(), $role);
        $this->redirect('/admin?done=role');
    }

    public function deleteUser(int $id): void
    {
        $user = $this->findOtherUser($id);

        $this->userRepository->delete($user->getId());
        $this->redirect('/admin?done=user-deleted');
    }

    public function deleteLoop(int $id): void
    {
        $this->requireRole($this->userRepository, User::ROLE_ADMIN);
        $this->requireCsrfToken();

        if ($this->loopRepository->find($id) === null) {
            $this->renderNotFound();
            exit;
        }

        $this->loopRepository->delete($id);
        $this->redirect('/admin?done=loop-deleted');
    }

    // wspólny początek akcji na koncie: admin + CSRF + konto istnieje + to nie jest własne konto
    private function findOtherUser(int $id): User
    {
        $admin = $this->requireRole($this->userRepository, User::ROLE_ADMIN);
        $this->requireCsrfToken();

        $user = $this->userRepository->find($id);

        if ($user === null) {
            $this->renderNotFound();
            exit;
        }

        // admin nie zmienia roli ani nie usuwa samego siebie, żeby nie zablokować sobie panelu
        if ($user->getId() === $admin->getId()) {
            $this->forbidden('You cannot change or delete your own account here.');
        }

        return $user;
    }
}
