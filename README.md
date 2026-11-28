# CookUP+

Projekt zaliczeniowy na przedmiot WDPAI.

CookUP+ to miejsce dla beatmakerów do wymiany pętli audio i wspólnego tworzenia utworów. Jeden użytkownik wrzuca pętlę (np. melodię), inni ją pobierają, dorabiają swoją część (np. perkusję) i odsyłają ją jako collab. Aplikacja umożliwia ciągłe odtwarzanie muzyki podczas przeglądania bazy, łatwe filtrowanie i wgrywanie plików. Ma dwa poziomy kont, automatyczny newsletter, pełną responsywność, tryb ciemny/jasny oraz interfejs po polsku i angielsku.

## Spis treści
- [Funkcje](#funkcje)
- [Role i uprawnienia](#role-i-uprawnienia)
- [Ekrany](#ekrany)
- [Flow aplikacji](#flow-aplikacji)
- [Technologie](#technologie)
- [Architektura](#architektura)
- [Routing / endpointy](#routing--endpointy)
- [Baza danych (ERD)](#baza-danych-erd)
- [Uruchomienie](#uruchomienie)
- [Bezpieczeństwo](#bezpieczeństwo)
- [Dostępność (WCAG)](#dostępność-wcag)
- [Testy](#testy)
- [Checklista wymagań](#checklista-wymagań)

## Funkcje
- Przeglądanie, wyszukiwanie i filtrowanie pętli (gatunek, BPM, tonacja, sortowanie) bez przeładowania strony (Fetch API).
- Odsłuchiwanie pętli w odtwarzaczu na dolnym pasku. Odtwarzacz zapamiętuje utwór i pozycję i wznawia odtwarzanie po przejściu na inną stronę.
- Wgrywanie pętli: plik audio (MP3/WAV) i okładka (JPG/PNG/WEBP). Pliki są zapisywane na serwerze, a w bazie przechowywany jest tylko URL.
- Pobieranie pętli i dodawanie ich do ulubionych.
- Collab: użytkownik wysyła swoją wersję pętli, a właściciel ją akceptuje albo odrzuca. Zaakceptowane collaby są widoczne pod pętlą.
- Profil użytkownika z avatarem, opisem, jego pętlami i ulubionymi.
- Newsletter: raz w tygodniu (CRON + PHPMailer) zapisani użytkownicy dostają maila z pętlami dodanymi w ostatnich 7 dniach.
- Tryb ciemny i jasny, interfejs PL i EN.

## Role i uprawnienia
| Rola | Uprawnienia |
|---|---|
| Gość | przegląda, filtruje i odsłuchuje pętle, widzi profile |
| Starter (domyślna) | wszystko co gość + pobieranie MP3, maks. 5 wgranych pętli, collaby, ulubione |
| Pro | wszystko co Starter + bez limitu pętli, pobieranie WAV |
| Admin | panel administracyjny: zmiana ról i usuwanie użytkowników, usuwanie pętli |

Przejście na Pro odbywa się przyciskiem „Upgrade” na stronie Pricing (bez płatności).

## Ekrany
1. Home
2. Loops - lista z wyszukiwarką i filtrami
3. Szczegóły pętli
4. Upload pętli
5. Collab - przychodzące i wysłane prośby
6. Profil
7. Pricing
8. Logowanie / rejestracja
9. Panel admina
10. Strony błędów (403, 404)

<!-- TODO: dodać zrzuty ekranu (desktop i mobile) -->

## Flow aplikacji
<!-- TODO: opisać główne ścieżki, np.:
1. Gość -> rejestracja -> logowanie
2. Użytkownik -> upload pętli -> pętla widoczna na liście
3. Inny użytkownik -> Request Collab -> właściciel akceptuje -> collab widoczny pod pętlą
-->

## Technologie
- PHP 8.3 (obiektowo, własne MVC i router)
- PostgreSQL
- JavaScript (Fetch API), HTML, CSS
- Docker Compose: nginx, php-fpm, postgres, pgAdmin, mailpit
- Composer: PHPMailer, PHPUnit

## Architektura
1. Nginx przekazuje każde żądanie do `public/index.php` (front controller).
2. W `index.php` są zarejestrowane trasy, np. `$routing->get('/loops/{id}', LoopController::class, 'show')`.
3. `Routing::dispatch()` zamienia każdą trasę na wyrażenie regularne (`{id}` pasuje tylko do cyfr), dopasowuje adres, tworzy kontroler i wywołuje jego metodę z parametrem, np. `show(1)`.
4. Kontroler pobiera dane (docelowo z repozytoriów PostgreSQL) i wywołuje `render()`.
5. `AppController::render()` przekazuje dane do widoku. Widoki korzystają ze wspólnych fragmentów (nagłówek, nawigacja, odtwarzacz).
6. Gdy żadna trasa nie pasuje, wyświetlany jest widok 404 z kodem odpowiedzi 404.

## Routing / endpointy
| Metoda | Ścieżka | Dostęp | Opis |
|---|---|---|---|
| GET | `/` | wszyscy | strona główna |
| GET | `/loops` | wszyscy | lista pętli |
| GET | `/loops/{id}` | wszyscy | szczegóły pętli (404 dla nieistniejącego id) |
| GET | `/pricing` | wszyscy | plany, przycisk zależy od roli |
| POST | `/pricing/upgrade` | Starter | zmiana roli na Pro, bez płatności |
| GET, POST | `/login` | gość | logowanie |
| GET, POST | `/register` | gość | rejestracja |
| POST | `/logout` | zalogowany | wylogowanie |
| GET | `/profile` | zalogowany | profil |
| GET | `/admin` | Admin | panel admina |
| POST | `/admin/users/{id}/role` | Admin | zmiana roli (400 dla nieznanej roli) |
| POST | `/admin/users/{id}/delete` | Admin | usunięcie konta |
| POST | `/admin/loops/{id}/delete` | Admin | usunięcie pętli |

- Uprawnienia sprawdza serwer na początku każdej akcji. Gość na stronie dla zalogowanych trafia na `/login` (303), a zalogowany bez wymaganej roli dostaje **403**.
- Rola jest czytana z bazy przy każdym żądaniu, więc zmiana roli w panelu admina działa od razu.
- Każdy formularz POST wymaga tokenu CSRF (bez niego 403). `{id}` przyjmuje tylko cyfry, a nieistniejące id daje 404.

## Baza danych (ERD)
Obecny schemat (PostgreSQL):

```mermaid
erDiagram
    LOOPS {
        bigint id PK "GENERATED ALWAYS AS IDENTITY"
        varchar title "NOT NULL, max 150"
        varchar producer "NOT NULL"
        varchar genre "NOT NULL, indeks"
        smallint bpm "NOT NULL, CHECK 40-300"
        varchar musical_key "NOT NULL"
        integer duration_seconds "NOT NULL, CHECK > 0"
        text description "NULL"
        timestamptz created_at "NOT NULL, DEFAULT now, indeks"
        timestamptz updated_at "NOT NULL, DEFAULT now"
    }
    USERS {
        bigint id PK "GENERATED ALWAYS AS IDENTITY"
        varchar name "NOT NULL, max 120"
        varchar email "NOT NULL, UNIQUE (bez względu na wielkość liter)"
        varchar password_hash "NOT NULL, wynik password_hash()"
        varchar role "NOT NULL, DEFAULT starter, CHECK starter/pro/admin"
        boolean newsletter "NOT NULL, DEFAULT false"
        timestamptz created_at "NOT NULL, DEFAULT now"
    }
```

<!-- TODO: docelowe relacje, dodawane razem z kolejnymi funkcjami:
    USERS ||--o{ LOOPS : uploads
    GENRES ||--o{ LOOPS : categorizes
    USERS ||--o{ FAVORITES : adds
    LOOPS ||--o{ FAVORITES : in
    LOOPS ||--o{ COLLABS : receives
    USERS ||--o{ COLLABS : sends
-->

Schemat bazy: `docker/db/init.sql` - PostgreSQL wykonuje go automatycznie przy pierwszym starcie z pustym wolumenem.
Eksport bazy do pliku .sql: <!-- TODO -->

## Uruchomienie
```bash
cp .env.example .env
docker compose up --build -d
docker compose exec php composer install
# przykładowe pętle (dodawane przez aplikację, uruchomienie drugi raz niczego nie dubluje)
docker compose exec php php scripts/seed-loops.php
# konta demo, po jednym dla każdej roli (też bez dublowania)
docker compose exec php php scripts/seed-users.php
```

### Konta demo
Hasło do wszystkich kont: `demo1234`

| Rola | E-mail | Co można pokazać |
|---|---|---|
| Starter | `starter@cookup.test` | Upgrade na Pro na stronie Pricing, wejście na `/admin` kończy się 403 |
| Pro | `pro@cookup.test` | plan Pro oznaczony jako obecny |
| Admin | `admin@cookup.test` | panel admina (link na profilu): zmiana ról, usuwanie kont i pętli |

Podgląd tabeli w bazie:
```bash
docker compose exec db sh -c 'psql -U "$POSTGRES_USER" -d "$POSTGRES_DB" -c "\d loops"'
```

Po zmianie `docker/db/init.sql` na bazie, która już istnieje (plik można uruchomić wielokrotnie):
```bash
docker compose exec -T db sh -c 'psql -U "$POSTGRES_USER" -d "$POSTGRES_DB"' < docker/db/init.sql
```
- Aplikacja: http://localhost:8080
- Mailpit: http://localhost:8025

## Bezpieczeństwo
<!-- TODO: opisać realizację security bingo -->
- Hasła hashowane przez `password_hash`.
- Zapytania przez prepared statements (PDO).
- Token CSRF w formularzach.
- Escapowanie danych w widokach (`htmlspecialchars`).
- Regeneracja ID sesji po zalogowaniu, ciasteczka HttpOnly i SameSite.
- Walidacja uploadu: typ MIME, rozmiar, losowa nazwa pliku.
- Uprawnienia sprawdzane po stronie serwera.
- Sekrety w `.env` (poza repozytorium).

## Dostępność (WCAG)
<!-- TODO: wstawić raport (np. Lighthouse / WAVE) -->

## Testy
```bash
docker compose exec -T php vendor/bin/phpunit
```
<!-- TODO -->

## Checklista wymagań
- [ ] Dokumentacja w README.md (ze screenami, opisami, flow aplikacji)
- [ ] Docker
- [ ] Architektura aplikacji MVC
- [ ] Routing: regex pattern, path parameter (id), query param
- [ ] Kod napisany obiektowo (część backendowa)
- [ ] Diagram ERD w README.md (Mermaid)
- [ ] Git (systematyka commitów, całość zmergowana do main)
- [ ] Realizacja tematu
- [ ] HTML
- [ ] PostgreSQL
- [ ] Złożoność bazy danych
- [ ] Eksport bazy do pliku .sql
- [ ] PHP
- [ ] JavaScript
- [ ] Fetch API (AJAX)
- [ ] Design
- [ ] Responsywność
- [ ] Tryb dark/light (data-theme na `<html>`, zapis w localStorage, prefers-color-scheme, skrypt anty-FOUC w `<head>`)
- [ ] Zgodność z WCAG (raport w README.md)
- [ ] Logowanie
- [ ] Sesja użytkownika
- [ ] Uprawnienia użytkowników
- [ ] Role użytkowników (co najmniej dwie)
- [ ] Wylogowywanie
- [ ] Widoki, wyzwalacze, funkcje, transakcje
- [ ] Akcje na referencjach
- [ ] Bezpieczeństwo (security bingo)
- [ ] Brak replikacji kodu
- [ ] Czystość i przejrzystość kodu
- [ ] Tłumaczenia (PL, EN)
- [ ] Unit testy
- [ ] CRON
- [ ] PHPMailer
- [ ] Upload zdjęć (plik na serwerze, w bazie tylko URL)
- [ ] Zmienne w .env
- [ ] Deploy
