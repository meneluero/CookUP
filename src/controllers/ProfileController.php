<?php

declare(strict_types=1);

namespace App\Controller;

use App\Data\LoopCatalog;

final class ProfileController extends AppController
{
    public function index(): void
    {
        $this->render('profile', [
            'pageTitle' => 'Marx Wave | CookUP+',
            'activeNav' => 'profile',
            'producer' => [
                'name' => 'Marx Wave',
                'handle' => '@marx_wav',
                'role' => 'Pro master vault • Tier 01 artist',
                'bio' => 'Multi-platinum producer & sound designer based in Los Angeles, CA. Specializing in warm analog Rhodes keys, vintage tape saturation, and authentic SP-1200 boom-bap rhythm sections.',
                'daw' => 'Ableton Live 12 + Pro Tools',
                'masterBus' => 'Studer A80 1/4"',
                'activeSince' => '2023',
            ],
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
