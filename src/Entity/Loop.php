<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeImmutable;
use LogicException;

/*
 Encja pętli - pola zgodne z modelem domeny z AGENTS.md i tabelą loops.
 Nowa pętla ma id = null; nadaje je PostgreSQL, a repozytorium ustawia je przez setId().
 Plik audio i okładka dojdą razem z uploadem, a $producer
 zastąpi id autora, gdy pojawi się tabela użytkowników.
*/
final class Loop
{
    public function __construct(
        private ?int $id,
        private readonly string $title,
        private readonly string $producer,
        private readonly string $genre,
        private readonly int $bpm,
        private readonly string $musicalKey,
        private readonly int $durationSeconds,
        private readonly ?string $description,
        private readonly ?DateTimeImmutable $createdAt = null,
        private readonly ?DateTimeImmutable $updatedAt = null,
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    // id ustawiamy tylko raz - po zapisie nowej pętli w bazie
    public function setId(int $id): void
    {
        if ($this->id !== null) {
            throw new LogicException('Loop already has an id.');
        }

        $this->id = $id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
