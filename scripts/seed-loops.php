<?php

declare(strict_types=1);

// Dodaje przykładowe pętle do bazy przez aplikację (LoopRepository::save()).
// Uruchomienie w terminalu WSL, w katalogu projektu:
//   docker compose exec php php scripts/seed-loops.php
// Gdy w tabeli są już pętle, skrypt nic nie robi, żeby nie dublować danych.

use App\Database;
use App\Entity\Loop;
use App\Repository\LoopRepository;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$repository = new LoopRepository(new Database());

if ($repository->findAll() !== []) {
    echo "Table loops already has data - nothing to do.\n";
    exit(0);
}

$loops = [
    new Loop(null, 'Midnight Tape V2 - Soul Chords', '@marx_wav', 'R&B / Soul', 130, 'F# minor', 32, 'Captured through 1978 Studer A80 1/4" 2-track master tape machine. Warm harmonic low-end saturation, pristine mechanical Rhodes Mk II dynamics, and authentic vinyl noise floor.'),
    new Loop(null, 'Obsidian Sub 808 & Textures', '@nrsma', 'Trap / Sub', 144, 'C minor', 32, 'Deep sub 808 layers with granular textures, tuned for modern trap low-end.'),
    new Loop(null, 'Tokyo Rain Vintage Rhodes', '@akira_beats', 'Neo-Soul / Lofi', 88, 'Eb major', 43, 'Rainy late-night Rhodes voicings recorded through a vintage tape echo.'),
    new Loop(null, 'Analog Prophet Brass Stabs', '@retrograde', 'Synthwave / Drill', 148, 'Bb minor', 43, 'Punchy polysynth brass stabs from a Prophet-5, perfect for drill and synthwave.'),
    new Loop(null, 'Velvet Guitar Lick 135', '@luna_stems', 'Acoustic / Indie', 135, 'A minor', 28, 'Warm nylon-string guitar lick with subtle room ambience.'),
];

foreach ($loops as $loop) {
    $repository->save($loop);
    printf("Added loop #%d: %s\n", $loop->getId(), $loop->getTitle());
}
