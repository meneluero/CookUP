<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\LoopRepository;
use App\Repository\UserRepository;

final class ProfileController extends AppController
{
    public function __construct(
        private readonly LoopRepository $loopRepository,
        private readonly UserRepository $userRepository,
    ) {
    }

    public function index(): void
    {
        $user = $this->userRepository->find($this->requireLogin());

        // konto usunięte w trakcie sesji - kończymy sesję, żeby nie krążyć między /login a /profile
        if ($user === null) {
            $this->destroySession();
            $this->redirect('/login');
        }

        $this->render('profile', [
            'pageTitle' => $user->getName() . ' | CookUP+',
            'activeNav' => 'profile',
            'user' => $user,
            'stats' => [
                ['label' => 'Total plays', 'value' => '1.42M', 'note' => 'Across 42 DSP aggregators'],
                ['label' => 'Stem downloads', 'value' => '48.2K', 'note' => 'Direct multi-track access'],
                ['label' => 'Active loop vaults', 'value' => '24', 'note' => 'Published & synchronized'],
                ['label' => 'Royalty clear rate', 'value' => '99.4%', 'note' => '100% sync ready'],
            ],
            // TODO: tylko pętle tego użytkownika, gdy upload przypisze pętli autora (loops.user_id)
            'loops' => $this->loopRepository->findAll(),
        ]);
    }
}
