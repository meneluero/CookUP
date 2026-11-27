-- Schemat bazy CookUP+.
-- PostgreSQL wykonuje ten plik automatycznie przy pierwszym starcie z pustym wolumenem
-- (docker-compose.yaml podpina katalog docker/db jako /docker-entrypoint-initdb.d).
-- Ręcznie: docker compose exec -T db sh -c 'psql -U "$POSTGRES_USER" -d "$POSTGRES_DB"' < docker/db/init.sql

CREATE TABLE IF NOT EXISTS loops (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    producer VARCHAR(50) NOT NULL,
    genre VARCHAR(50) NOT NULL,
    bpm SMALLINT NOT NULL,
    musical_key VARCHAR(20) NOT NULL,
    duration_seconds INTEGER NOT NULL,
    description TEXT NULL,
    created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,
    -- baza sama pilnuje sensownych wartości, niezależnie od kodu PHP
    CONSTRAINT loops_bpm_check CHECK (bpm BETWEEN 40 AND 300),
    CONSTRAINT loops_duration_check CHECK (duration_seconds > 0)
);

-- indeksy pod filtrowanie po gatunku i sortowanie od najnowszych
CREATE INDEX IF NOT EXISTS loops_genre_idx ON loops (genre);
CREATE INDEX IF NOT EXISTS loops_created_at_idx ON loops (created_at DESC);

CREATE TABLE IF NOT EXISTS users (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(255) NOT NULL,
    -- wynik password_hash(), nigdy samo hasło; 255 znaków zostawia miejsce na inne algorytmy
    password_hash VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL DEFAULT 'starter',
    newsletter BOOLEAN NOT NULL DEFAULT FALSE,
    created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT users_role_check CHECK (role IN ('starter', 'pro', 'admin'))
);

-- jeden e-mail = jedno konto, bez względu na wielkość liter (ostatnia linia obrony przy rejestracji)
CREATE UNIQUE INDEX IF NOT EXISTS users_email_unique ON users (LOWER(email));
