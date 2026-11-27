# AGENTS.md

## Cel projektu
CookUP+ to projekt na przedmiot WDPAI. Jest to miejsce dla beatmakerów do wymiany pętli audio
i wspólnego tworzenia utworów. Jeden użytkownik wrzuca pętlę, inni ją pobierają, dorabiają swoją
część i odsyłają ją jako collab.

Priorytet: najprostsze działające rozwiązanie, które spełnia wymagania prowadzącego.
Figma jest tylko inspiracją wizualną, nie odwzorowujemy jej 1:1.
Pełna lista funkcji, ekranów i checklista wymagań jest w `README.md`.

## Model domeny
- **Pętla (loop)** - jeden plik audio + okładka + tytuł, gatunek, BPM i tonacja.
- **Collab** - plik audio innego użytkownika powiązany z pętlą. Ma status: oczekuje, zaakceptowany albo odrzucony.
- Nie robimy stemów, paczek, utworów, sesji, czatu ani wersjonowania.

## Role i uprawnienia
- **Gość** - przegląda, filtruje i odsłuchuje pętle, widzi profile.
- **Starter** (rola domyślna) - może wgrać maksymalnie 5 pętli i pobierać tylko MP3.
- **Pro** - bez limitu pętli, pobieranie WAV. Przycisk „Upgrade” na stronie Pricing tylko zmienia rolę, nie ma płatności.
- **Admin** - prosty panel: lista użytkowników (zmiana roli, usuwanie) i lista pętli (usuwanie).
- Limit 5 pętli dla Startera sprawdzamy w PHP i dodatkowo wyzwalaczem w bazie.
- Uprawnienia zawsze sprawdzamy po stronie serwera.

## Stack
- PHP 8.3 obiektowo, własne MVC (Controller / Repository / View).
- Własny router na regexach - parametry ścieżki (np. `/loops/{id}`) i query (np. `?genre=trap&bpm_min=120`).
- PostgreSQL przez PDO z prepared statements.
- Czysty JavaScript (Fetch API) i czysty CSS. Bez npm, bundlerów i frameworków.
- Composer tylko dla PHPMailer i PHPUnit.
- Docker Compose: nginx, php-fpm (z cronem), postgres, mailpit i pgAdmin do pracy lokalnej.
  W compose jest też Ollama - zostaje, dopóki nie wiadomo, czy któreś laboratorium jej wymaga.
- Konfiguracja w `.env`, w repo trzymamy `.env.example`.

## Struktura
Obecnie:
- `public/index.php` - punkt wejścia, tutaj rejestrujemy trasy.
- `src/Routing.php` - dopasowanie tras i uruchamianie kontrolerów.
- `src/controllers/` - kontrolery w namespace `App\Controller`, dziedziczą po `AppController`.
  Mapowanie jest ustawione w `composer.json`.
- `src/Entity/` - encje w namespace `App\Entity` (`Loop`, `User` ze stałymi ról): prywatne pola
  i gettery. Kontrolery przekazują do widoków encje, nie tablice.
- `src/Repository/` - repozytoria w namespace `App\Repository`, dziedziczą po `Repository`.
  Cały SQL jest tutaj (prepared statements), wiersze z bazy mapujemy na encje.
- `src/Database.php` + `config.php` - połączenie PDO z PostgreSQL. Dane logowania z `.env`
  (przez `environment` usługi `php` w `docker-compose.yaml`), połączenie otwierane przy pierwszym zapytaniu.
- Zależności (repozytoria) tworzymy w `public/index.php` i przekazujemy kontrolerom przez fabryki w `Routing`.
- Sesja startuje tylko w `public/index.php` (strict mode, ciasteczko HttpOnly + SameSite=Lax).
  W sesji trzymamy tylko `user_id`, `user_name` i `csrf_token`.
- `AppController` - wspólne metody: `isPost()`, `redirect()` (303), `requireLogin()`, token CSRF,
  `destroySession()`. `render()` dodaje do każdego widoku `csrfToken` i `currentUserName`.
- `SecurityController` - rejestracja, logowanie i wylogowanie (POST + CSRF). Chronione strony
  wywołują `requireLogin()` na początku akcji (np. `/profile`).
- `src/Data/LoopCatalog.php` - tymczasowe dane testowe (stemy, komentarze), których nie ma jeszcze w bazie.
- `docker/db/init.sql` - schemat bazy, wykonywany automatycznie przy pierwszym starcie z pustym wolumenem.
  Tam też trafi eksport bazy do `.sql`.
- `scripts/seed-loops.php` - przykładowe pętle dodawane przez `LoopRepository`.
- `public/views/` - widoki `*.html.php`, wspólne fragmenty są w `public/views/partials/`.
  Wspólna strona błędów: `error.html.php` (`AppController::renderError()`).
- `public/css/style.css` - jeden wspólny arkusz, zmienne w `:root`, podejście mobile first.
- `public/js/` - czysty JS ładowany z `defer` w `partials/head.html.php` (bez `onclick` w HTML,
  bez `innerHTML` z danymi użytkownika): `theme.js` (motyw), `forms.js` (walidacja w przeglądarce,
  np. `data-match` dla powtórzenia hasła; teksty komunikatów podaje PHP w atrybutach `data-`).
- `docker/` - Dockerfile dla nginx, php i bazy.

Planowane (jeszcze ich nie ma):
- `lang/` - tłumaczenia `pl.php` i `en.php`.
- `public/uploads/` - pliki użytkowników, z blokadą wykonywania PHP w nginx.
- plik crona i skrypt newslettera.
- `tests/` - testy PHPUnit.

## Zasady pracy
- Rób tylko to, o co poproszono. Nie rozbudowuj ponad zakres. Jeśli coś jest niejasne, wybierz prostszą opcję i to zaznacz.
- Kod (klasy, zmienne, tabele) po angielsku. Komentarze krótko po polsku, tylko tam, gdzie coś nie jest oczywiste.
- W plikach PHP zostawiaj `declare(strict_types=1)`.
- Kontroler przygotowuje dane, a widok tylko je wyświetla.
- Dane wyświetlane w HTML zabezpieczaj przez `htmlspecialchars()`.
- Bez duplikacji kodu: wspólny layout i komponenty, bazowy Controller i Repository.
- Interfejs po polsku (domyślnie) i po angielsku.
- Nie dodawaj frameworków ani bibliotek bez pytania.
- Commity małe, w formacie `feat:`, `fix:`, `refactor:`, `docs:`, `test:`, `style:`, `chore:`. Commituj tylko na wyraźną prośbę.
- Agent nie commituje ani nie pushuje - robi to autor. Agent proponuje tylko treść commita.

## Uruchamianie i weryfikacja
- Start (w terminalu WSL, w katalogu projektu): `docker compose up --build -d`
- Aplikacja: http://localhost:8080, Mailpit: http://localhost:8025 (domyślne porty)
- Dane przykładowe: `docker compose exec -T php php scripts/seed-loops.php`
- Baza (psql): `docker compose exec db sh -c 'psql -U "$POSTGRES_USER" -d "$POSTGRES_DB"'`
- Sprawdzenie składni: `docker compose exec -T php php -l ścieżka/do/pliku.php`
- Testy (jak już będą): `docker compose exec -T php vendor/bin/phpunit`
- Po zmianie trasy sprawdź poprawny adres i odpowiedź 404 dla nieistniejącego adresu.
- Przed zakończeniem pokaż `git diff` i podsumuj zmienione pliki.

## Bezpieczeństwo
- Hasła przez `password_hash`, zapytania przez prepared statements, token CSRF w formularzach.
- Po zalogowaniu regeneruj ID sesji. Ciasteczka z flagami HttpOnly i SameSite.
- Upload sprawdzaj po stronie serwera: typ MIME, rozmiar i losowa nazwa pliku.
- Widoki w `public/views/` są teraz dostępne bezpośrednio z przeglądarki. Planujemy przenieść je
  do `src/views/` albo zablokować `/views` w nginx. Nie dodawaj tam nowych plików wykonywalnych poza widokami.
- Nie odczytuj ani nie commituj sekretów z `.env`.
- Nie ruszaj `vendor/` ani `uploads/` (pliki użytkowników).
- Nie wykonuj destrukcyjnych poleceń bez potwierdzenia.

## Plany na później
- Folder `src/controllers/` zmienimy na `src/Controller/`, żeby pasował do namespace (teraz działa dzięki mapowaniu w `composer.json`).
- Widoki przeniesiemy poza `public/` albo zablokujemy je w nginx (patrz Bezpieczeństwo).
