<?php

declare(strict_types=1);

namespace App\Data;

use App\Entity\Loop;

/*
 Tymczasowe dane testowe (zamiast bazy danych).
 Wiersze mają ten sam kształt co przyszła tabela loops, a toEntity()
 to mapowanie wiersza na encję, które przejmie repozytorium z PostgreSQL.
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
            'musical_key' => 'F# minor',
            'duration_seconds' => 32,
            'description' => 'Captured through 1978 Studer A80 1/4" 2-track master tape machine. Warm harmonic low-end saturation, pristine mechanical Rhodes Mk II dynamics, and authentic vinyl noise floor.',
        ],
        2 => [
            'id' => 2,
            'title' => 'Obsidian Sub 808 & Textures',
            'producer' => '@nrsma',
            'genre' => 'Trap / Sub',
            'bpm' => 144,
            'musical_key' => 'C minor',
            'duration_seconds' => 32,
            'description' => 'Deep sub 808 layers with granular textures, tuned for modern trap low-end.',
        ],
        3 => [
            'id' => 3,
            'title' => 'Tokyo Rain Vintage Rhodes',
            'producer' => '@akira_beats',
            'genre' => 'Neo-Soul / Lofi',
            'bpm' => 88,
            'musical_key' => 'Eb major',
            'duration_seconds' => 43,
            'description' => 'Rainy late-night Rhodes voicings recorded through a vintage tape echo.',
        ],
        4 => [
            'id' => 4,
            'title' => 'Analog Prophet Brass Stabs',
            'producer' => '@retrograde',
            'genre' => 'Synthwave / Drill',
            'bpm' => 148,
            'musical_key' => 'Bb minor',
            'duration_seconds' => 43,
            'description' => 'Punchy polysynth brass stabs from a Prophet-5, perfect for drill and synthwave.',
        ],
        5 => [
            'id' => 5,
            'title' => 'Velvet Guitar Lick 135',
            'producer' => '@luna_stems',
            'genre' => 'Acoustic / Indie',
            'bpm' => 135,
            'musical_key' => 'A minor',
            'duration_seconds' => 28,
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

    /**
     * @return Loop[]
     */
    public static function all(): array
    {
        return array_values(array_map(self::toEntity(...), self::LOOPS));
    }

    public static function find(int $id): ?Loop
    {
        return isset(self::LOOPS[$id]) ? self::toEntity(self::LOOPS[$id]) : null;
    }

    public static function stems(): array
    {
        return self::STEMS;
    }

    public static function comments(): array
    {
        return self::COMMENTS;
    }

    private static function toEntity(array $row): Loop
    {
        return new Loop(
            id: $row['id'],
            title: $row['title'],
            producer: $row['producer'],
            genre: $row['genre'],
            bpm: $row['bpm'],
            musicalKey: $row['musical_key'],
            durationSeconds: $row['duration_seconds'],
            description: $row['description'],
        );
    }
}
