<?php

declare(strict_types=1);

namespace App\Entity;

/*
 Encja pętli - pola zgodne z modelem domeny z AGENTS.md.
 Plik audio i okładka dojdą razem z uploadem, a $producer
 zastąpi id autora, gdy pojawi się tabela użytkowników.
*/
final readonly class Loop
{
    public function __construct(
        private int $id,
        private string $title,
        private string $producer,
        private string $genre,
        private int $bpm,
        private string $musicalKey,
        private int $durationSeconds,
        private string $description,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getProducer(): string
    {
        return $this->producer;
    }

    public function getGenre(): string
    {
        return $this->genre;
    }

    public function getBpm(): int
    {
        return $this->bpm;
    }

    public function getMusicalKey(): string
    {
        return $this->musicalKey;
    }

    public function getDurationSeconds(): int
    {
        return $this->durationSeconds;
    }

    // np. 92 sekundy -> "1:32"
    public function getFormattedDuration(): string
    {
        return sprintf('%d:%02d', intdiv($this->durationSeconds, 60), $this->durationSeconds % 60);
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}
