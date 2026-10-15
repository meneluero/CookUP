<?php

declare(strict_types=1);

namespace App\Controller;

use App\Data\LoopCatalog;

final class DefaultController extends AppController
{
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
            'loops' => LoopCatalog::all(),
            'collections' => [
                ['label' => 'Staff pick', 'title' => 'Analog Tape Saturations V1', 'details' => '42 presets • Studer A80 imprints'],
                ['label' => 'Series drop', 'title' => 'Modular Sub Harmonic Rhythms', 'details' => '86 loops • 100% royalty free'],
                ['label' => 'Community', 'title' => 'Neo-Classical Chord Voicings', 'details' => 'MIDI + audio multi-track stems'],
            ],
        ]);
    }

    public function pricing(): void
    {
        $this->render('pricing', [
            'pageTitle' => 'Pricing | CookUP+',
            'activeNav' => 'pricing',
            'plans' => [
                [
                    'tier' => 'Community / Essentials',
                    'name' => 'CookUP Starter',
                    'price' => '$0',
                    'period' => 'month',
                    'note' => 'Free forever',
                    'description' => 'Essential workstation capabilities to audition loops, study arrangements, and build rapid offline demo drafts.',
                    'features' => [
                        '5 lossless WAV downloads per month',
                        'Standard 16-bit / 44.1kHz audio previews',
                        'Standard collaboration sync queue',
                        'Community sample vault read access',
                        'Personal non-commercial license',
                    ],
                    'cta' => 'Current tier (active)',
                    'highlighted' => false,
                ],
                [
                    'tier' => 'Pro Studio System',
                    'name' => 'CookUP+ Pro Studio',
                    'price' => '$24',
                    'period' => 'month',
                    'note' => 'Billed annually ($288/yr) — 2 months complimentary',
                    'description' => 'Zero-latency workflow, unrestricted multi-track stems, and direct collaboration engine for commercial records and singer publishing.',
                    'features' => [
                        'Unlimited 24-bit / 48kHz lossless WAV & MIDI downloads',
                        'Full multi-track stem breakdown inspector access',
                        'Priority collaboration engine & instant topline requests',
                        '100% commercial clearance & automated sync licences',
                        'Zero-latency cloud audio engine (<4ms jitter lock)',
                        'Unlimited loop uploads & producer vault analytics',
                        'Private producer card & verified blue master badge',
                        'Early access to weekly curated series drops',
                    ],
                    'cta' => 'Upgrade to Pro Studio',
                    'highlighted' => true,
                ],
            ],
            'specification' => [
                ['feature' => 'Audio fidelity & bitrate', 'starter' => '16-bit / 44.1kHz MP3', 'pro' => '24-bit / 48kHz WAV / Lossless'],
                ['feature' => 'Stem track breakdown', 'starter' => 'Stereo master only', 'pro' => 'Unrestricted multi-stems (individual tracks)'],
                ['feature' => 'Commercial rights & sync', 'starter' => 'Non-commercial / demo', 'pro' => '100% royalty cleared + sync certificate'],
                ['feature' => 'Collaboration engine', 'starter' => 'Standard async (queued)', 'pro' => 'Direct peer-to-peer + topline requests'],
                ['feature' => 'DAW MIDI & drum kits', 'starter' => 'Audio only', 'pro' => 'Bundled MIDI maps + sampler presets'],
                ['feature' => 'Producer vault storage', 'starter' => '1.0 GB cloud', 'pro' => '500.0 GB high-throughput SSD'],
            ],
            'faq' => [
                ['question' => 'What happens to my beats if I cancel Pro?', 'answer' => 'Any tracks or stems downloaded during your active Pro membership retain perpetual commercial clearance. Your master sync licenses remain 100% valid indefinitely.'],
                ['question' => 'Are the stems dry or wet?', 'answer' => 'All Pro stems provide both dry takes and fully processed wet arrangements, plus discrete MIDI files whenever the author included synth or key voicings.'],
                ['question' => 'Can I use CookUP+ stems for major label placements?', 'answer' => 'Yes. Pro Studio memberships include complete clearing agreements compliant with Billboard Hot 100, streaming DSPs, and sync placements.'],
                ['question' => 'How does the 4ms audio jitter lock function?', 'answer' => 'Our custom WebAudio DSP synchronizes cloud stem audition directly with your operating system\'s hardware buffer size, ensuring glitch-free instant previewing.'],
            ],
        ]);
    }

    public function notFound(): void
    {
        $this->renderNotFound();
    }
}
