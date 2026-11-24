<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Loop;
use DateTimeImmutable;
use InvalidArgumentException;

// Cały SQL dotyczący pętli. Dane wychodzą stąd jako encje Loop, nie tablice.
final class LoopRepository extends Repository
{
    private const COLUMNS = 'id, title, producer, genre, bpm, musical_key, duration_seconds, description, created_at, updated_at';

    /**
     * @return list<Loop>
     */
    public function findAll(): array
    {
        // stałe zapytanie bez danych z zewnątrz - wystarczy query()
        $statement = $this->connection()->query(
            'SELECT ' . self::COLUMNS . ' FROM loops ORDER BY created_at DESC, id DESC'
        );

        return array_map($this->mapRowToLoop(...), $statement->fetchAll());
    }

    public function find(int $id): ?Loop
    {
        $statement = $this->connection()->prepare(
            'SELECT ' . self::COLUMNS . ' FROM loops WHERE id = :id'
        );
        $statement->execute(['id' => $id]);

        $row = $statement->fetch();

        return $row === false ? null : $this->mapRowToLoop($row);
    }

    public function save(Loop $loop): void
    {
        if ($loop->getId() !== null) {
            throw new InvalidArgumentException('Loop is already saved - use update().');
        }

        $statement = $this->connection()->prepare(
            'INSERT INTO loops (title, producer, genre, bpm, musical_key, duration_seconds, description)
             VALUES (:title, :producer, :genre, :bpm, :musical_key, :duration_seconds, :description)
             RETURNING id'
        );
        $statement->execute($this->toParameters($loop));

        $loop->setId((int) $statement->fetchColumn());
    }

    public function update(Loop $loop): void
    {
        if ($loop->getId() === null) {
            throw new InvalidArgumentException('Cannot update a loop without an id.');
        }

        $statement = $this->connection()->prepare(
            'UPDATE loops
             SET title = :title,
                 producer = :producer,
                 genre = :genre,
                 bpm = :bpm,
                 musical_key = :musical_key,
                 duration_seconds = :duration_seconds,
                 description = :description,
                 updated_at = CURRENT_TIMESTAMP
             WHERE id = :id'
        );
        $statement->execute(['id' => $loop->getId()] + $this->toParameters($loop));
    }

    public function delete(int $id): void
    {
        $statement = $this->connection()->prepare('DELETE FROM loops WHERE id = :id');
        $statement->execute(['id' => $id]);
    }

    /**
     * @return array<string, int|string|null>
     */
    private function toParameters(Loop $loop): array
    {
        return [
            'title' => $loop->getTitle(),
            'producer' => $loop->getProducer(),
            'genre' => $loop->getGenre(),
            'bpm' => $loop->getBpm(),
            'musical_key' => $loop->getMusicalKey(),
            'duration_seconds' => $loop->getDurationSeconds(),
            'description' => $loop->getDescription(),
        ];
    }

    /**
     * @param array<string, mixed> $row
     */
    private function mapRowToLoop(array $row): Loop
    {
        return new Loop(
            id: (int) $row['id'],
            title: (string) $row['title'],
            producer: (string) $row['producer'],
            genre: (string) $row['genre'],
            bpm: (int) $row['bpm'],
            musicalKey: (string) $row['musical_key'],
            durationSeconds: (int) $row['duration_seconds'],
            description: $row['description'] === null ? null : (string) $row['description'],
            createdAt: new DateTimeImmutable((string) $row['created_at']),
            updatedAt: new DateTimeImmutable((string) $row['updated_at']),
        );
    }
}
