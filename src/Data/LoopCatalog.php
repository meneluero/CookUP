<?php

declare(strict_types=1);

namespace App\Data;

/*
 Tymczasowe dane testowe dla widoku pętli (stemy i komentarze), których nie ma jeszcze w bazie.
 Same pętle są już w PostgreSQL - pobiera je LoopRepository.
*/
final class LoopCatalog
{
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

    public static function stems(): array
    {
        return self::STEMS;
    }

    public static function comments(): array
    {
        return self::COMMENTS;
    }
}
