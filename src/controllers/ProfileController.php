<?php

declare(strict_types=1);

namespace App\Controller;

use App\Data\LoopCatalog;
use App\Entity\User;
use DateTimeImmutable;

final class ProfileController extends AppController
{
    public function index(): void
    {
        // tymczasowo na sztywno - po dodaniu logowania to będzie zalogowany użytkownik
        $producer = new User(
            id: 1,
            name: 'Marx Wave',
            handle: '@marx_wav',
            bio: 'Multi-platinum producer & sound designer based in Los Angeles, CA. Specializing in warm analog Rhodes keys, vintage tape saturation, and authentic SP-1200 boom-bap rhythm sections.',
            role: User::ROLE_PRO,
            createdAt: new DateTimeImmutable('2023-03-01'),
        );

        $this->render('profile', [
            'pageTitle' => $producer->getName() . ' | CookUP+',
            'activeNav' => 'profile',
            'producer' => $producer,
            'stats' => [
                ['label' => 'Total plays', 'value' => '1.42M', 'note' => 'Across 42 DSP aggregators'],
                ['label' => 'Stem downloads', 'value' => '48.2K', 'note' => 'Direct multi-track access'],
                ['label' => 'Active loop vaults', 'value' => '24', 'note' => 'Published & synchronized'],
                ['label' => 'Royalty clear rate', 'value' => '99.4%', 'note' => '100% sync ready'],
            ],
            'loops' => LoopCatalog::all(),
        ]);
    }
}
