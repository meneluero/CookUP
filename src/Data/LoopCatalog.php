<?php

declare(strict_types=1);

namespace App\Data;

/*
 Tymczasowe dane testowe (zamiast bazy danych).
 Później zastąpimy je repozytorium pobierającym dane z PostgreSQL.
*/
final class LoopCatalog
{
    private const LOOPS = [
        1 => [
            'id' => 1,
            'title' => 'Midnight Tape V2 - Soul Chords',
            'producer' => '@marx_wav',
            'genre' => 'R&B / Soul',
            'bpm' => 130,
            'key' => 'F# minor',
            'bars' => 8,
            'duration' => '0:32',
            'plays' => '412.5k',
            'status' => 'Live',
            'description' => 'Captured through 1978 Studer A80 1/4" 2-track master tape machine. Warm harmonic low-end saturation, pristine mechanical Rhodes Mk II dynamics, and authentic vinyl noise floor.',
        ],
        2 => [
            'id' => 2,
            'title' => 'Obsidian Sub 808 & Textures',
            'producer' => '@nrsma',
            'genre' => 'Trap / Sub',
            'bpm' => 144,
            'key' => 'C minor',
            'bars' => 8,
            'duration' => '0:32',
            'plays' => '289.1k',
            'status' => 'Verified master',
            'description' => 'Deep sub 808 layers with granular textures, tuned for modern trap low-end.',
        ],
        3 => [
            'id' => 3,
            'title' => 'Tokyo Rain Vintage Rhodes',
            'producer' => '@akira_beats',
            'genre' => 'Neo-Soul / Lofi',
            'bpm' => 88,
            'key' => 'Eb major',
            'bars' => 8,
            'duration' => '0:43',
            'plays' => '194.0k',
            'status' => 'Live',
            'description' => 'Rainy late-night Rhodes voicings recorded through a vintage tape echo.',
        ],
        4 => [
            'id' => 4,
            'title' => 'Analog Prophet Brass Stabs',
            'producer' => '@retrograde',
            'genre' => 'Synthwave / Drill',
            'bpm' => 148,
            'key' => 'Bb minor',
            'bars' => 8,
            'duration' => '0:43',
            'plays' => '152.8k',
            'status' => 'Live',
            'description' => 'Punchy polysynth brass stabs from a Prophet-5, perfect for drill and synthwave.',
        ],
        5 => [
            'id' => 5,
            'title' => 'Velvet Guitar Lick 135',
            'producer' => '@luna_stems',
            'genre' => 'Acoustic / Indie',
            'bpm' => 135,
            'key' => 'A minor',
            'bars' => 8,
            'duration' => '0:28',
            'plays' => '—',
            'status' => 'In review',
            'description' => 'Warm nylon-string guitar lick with subtle room ambience.',
        ],
    ];

    private const STEMS = [
        ['name' => 'Rhodes Mk II Stage', 'details' => 'Analog Tape Preamp // Stereo'],
        ['name' => 'Sub Bass (Moog Minitaur)', 'details' => 'Direct In 24-bit // Mono 30Hz'],
        ['name' => 'Vinyl Floor & Crackle', 'details' => 'SP-1200 Sampling Unit // Stereo'],
        ['name' => 'Harmonic Vocal Chop', 'details' => 'Formant Shifted -2 ST // Reverb'],
        ['name' => 'Top Percussion & Shaker', 'details' => 'Live Acoustic Room Kit // Stereo'],
        ['name' => 'Chord Voicings (MIDI)', 'details' => 'Velocity Sensitive Micro-Timed // MIDI'],
    ];

    private const COMMENTS = [
        ['author' => '@metro_cook', 'time' => '12m ago', 'marker' => '00:46', 'text' => 'That tape flutter and Rhodes detune on the transition into Bar 16 is unreal. Added a dry rimshot over it and it instantly glued.'],
        ['author' => '@elena_beats', 'time' => '2h ago', 'marker' => '01:12', 'text' => 'Clean low end on the Minitaur stem. Didn\'t even need surgical EQ when dropping an 808 sidechain.'],
        ['author' => '@sora_sound', 'time' => '4h ago', 'marker' => '02:04', 'text' => 'Stems dragged and aligned effortlessly into Ableton Live 12. Tempo marker 130 locked straight out of the zip. 10/10 master.'],
        ['author' => '@k_apex', 'time' => '5h ago', 'marker' => '02:48', 'text' => 'Top percussion shaker pocket has the exact Dilla-style swing we needed for an R&B singer session tomorrow.'],
    ];

    public static function all(): array
    {
        return array_values(self::LOOPS);
    }

    public static function find(int $id): ?array
    {
        return self::LOOPS[$id] ?? null;
    }

    public static function stems(): array
    {
        return self::STEMS;
    }

    public static function comments(): array
    {
        return self::COMMENTS;
    }
}
