<?php

declare(strict_types=1);

namespace App\Controller;

use App\Data\LoopCatalog;

final class LoopController extends AppController
{
    public function index(): void
    {
        $loops = LoopCatalog::all();

        $this->render('loops', [
            'pageTitle' => 'Browse loops | CookUP+',
            'activeNav' => 'loops',
            'loops' => $loops,
            'loopCount' => count($loops),
            'genres' => ['All', 'Trap', 'Drill', 'R&B / Soul', 'Boom Bap', 'Neo-Classical', 'Dark Ambient', 'Guitar Stems'],
        ]);
    }

    public function show(int $id): void
    {
        $loop = LoopCatalog::find($id);

        if ($loop === null) {
            $this->renderNotFound();
            return;
        }

        $this->render('loop', [
            'pageTitle' => $loop['title'] . ' | CookUP+',
            'activeNav' => 'loops',
            'loop' => $loop,
            'stems' => LoopCatalog::stems(),
            'comments' => LoopCatalog::comments(),
        ]);
    }
}
