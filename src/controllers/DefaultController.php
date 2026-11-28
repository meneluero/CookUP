<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\LoopRepository;

final class DefaultController extends AppController
{
    public function __construct(private readonly LoopRepository $loopRepository)
    {
    }

    public function index(): void
    {
        $this->render('home', [
            'pageTitle' => 'Loops & Sample Vault | CookUP+',
            'activeNav' => 'home',
            'stats' => [
                ['label' => 'Vault index', 'value' => '2,418', 'note' => '+38 today'],
                ['label' => 'Stream protocol', 'value' => '48kHz', 'note' => 'Lossless 24-bit'],
                ['label' => 'Live producers', 'value' => '412', 'note' => 'Session room active'],
                ['label' => 'Engine jitter', 'value' => '<2.4ms', 'note' => 'Direct to disk'],
            ],
            'loops' => $this->loopRepository->findAll(),
            'collections' => [
                ['label' => 'Staff pick', 'title' => 'Analog Tape Saturations V1', 'details' => '42 presets • Studer A80 imprints'],
                ['label' => 'Series drop', 'title' => 'Modular Sub Harmonic Rhythms', 'details' => '86 loops • 100% royalty free'],
                ['label' => 'Community', 'title' => 'Neo-Classical Chord Voicings', 'details' => 'MIDI + audio multi-track stems'],
            ],
        ]);
    }

    public function notFound(): void
    {
        $this->renderNotFound();
    }

    public function serverError(): void
    {
        $this->renderError(500, 'Server error', 'Something went wrong on our side. Please try again later.');
    }
}
