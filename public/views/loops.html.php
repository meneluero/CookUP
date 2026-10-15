<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
</head>
<body>
    <?php require __DIR__ . '/partials/header.html.php'; ?>

    <main>
        <h1>Browse loops</h1>
        <p><?= $loopCount ?> curated stems &amp; loops</p>

        <section aria-labelledby="filters-heading">
            <h2 id="filters-heading">Filters</h2>
            <form action="/loops" method="get">
                <label for="loop-search">Search by loop name, producer handle, instrument or vibe</label>
                <input type="search" id="loop-search" name="q">

                <label for="bpm">BPM</label>
                <select id="bpm" name="bpm">
                    <option value="">Any</option>
                    <option value="80-100">80–100</option>
                    <option value="100-120">100–120</option>
                    <option value="120-140">120–140</option>
                    <option value="140-160">140–160</option>
                </select>

                <label for="key">Key</label>
                <select id="key" name="key">
                    <option value="">All keys</option>
                    <option value="minor">Minor</option>
                    <option value="major">Major</option>
                </select>

                <label for="sort">Sort</label>
                <select id="sort" name="sort">
                    <option value="latest">Latest drops</option>
                    <option value="trending">Trending</option>
                    <option value="plays">Most played</option>
                </select>

                <fieldset>
                    <legend>Genre</legend>
                    <?php foreach ($genres as $index => $genre): ?>
                        <label>
                            <input type="radio" name="genre" value="<?= htmlspecialchars($genre) ?>"<?= $index === 0 ? ' checked' : '' ?>>
                            <?= htmlspecialchars($genre) ?>
                        </label>
                    <?php endforeach; ?>
                </fieldset>

                <button type="submit">Apply filters</button>
            </form>
        </section>

        <section aria-labelledby="results-heading">
            <h2 id="results-heading">Results</h2>
            <ul>
                <?php foreach ($loops as $loop): ?>
                    <li>
                        <article>
                            <header>
                                <h3><a href="/loops/<?= $loop['id'] ?>"><?= htmlspecialchars($loop['title']) ?></a></h3>
                                <p>prod. <?= htmlspecialchars($loop['producer']) ?></p>
                            </header>
                            <ul>
                                <li><?= $loop['bpm'] ?> BPM</li>
                                <li><?= htmlspecialchars($loop['key']) ?></li>
                                <li><?= htmlspecialchars($loop['genre']) ?></li>
                                <li><?= $loop['bars'] ?> bars</li>
                            </ul>
                            <button type="button">Play</button>
                            <button type="button">Save</button>
                            <a href="/loops/<?= $loop['id'] ?>">View stems</a>
                        </article>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>

        <aside aria-labelledby="pack-heading">
            <h2 id="pack-heading">CookUP Pro Pack Vol. 03</h2>
            <p>Lossless 32-bit stems + MIDI patterns included.</p>
            <a href="/pricing">Download pack</a>
        </aside>
    </main>

    <?php require __DIR__ . '/partials/player.html.php'; ?>
</body>
</html>
