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
        <nav aria-label="Breadcrumb">
            <ol>
                <li><a href="/loops">Loops vault</a></li>
                <li><?= htmlspecialchars($loop['genre']) ?></li>
                <li aria-current="page"><?= htmlspecialchars($loop['title']) ?></li>
            </ol>
        </nav>

        <article>
            <header>
                <p>Verified master stem • <?= htmlspecialchars($loop['status']) ?></p>
                <h1><?= htmlspecialchars($loop['title']) ?></h1>
                <p>prod. <a href="/profile"><?= htmlspecialchars($loop['producer']) ?></a></p>
                <p><?= htmlspecialchars($loop['description']) ?></p>
                <button type="button">Add to favorites</button>
                <button type="button">Share</button>
                <a href="#stems-heading">Export stems (WAV + MIDI)</a>
            </header>

            <section aria-labelledby="waveform-heading">
                <h2 id="waveform-heading">Waveform</h2>
                <figure>
                    <!-- TODO: wizualizacja waveformu (canvas / SVG) -->
                    <figcaption>
                        Arrangement markers: Bar 1 Intro, Bar 8 Rhodes in, Bar 16 Main drop, Bar 24 Hook B, Bar 32 Bridge
                    </figcaption>
                </figure>
                <button type="button">Pause</button>
                <button type="button">Loop region</button>
                <label for="pitch">Pitch</label>
                <select id="pitch" name="pitch">
                    <option value="0">Original (0 st)</option>
                    <option value="-1">-1 st</option>
                    <option value="1">+1 st</option>
                </select>
                <p><time datetime="PT1M14S">01:14.28</time> / <time datetime="PT3M28S">03:28.00</time></p>
            </section>

            <section aria-labelledby="specs-heading">
                <h2 id="specs-heading">Specifications &amp; master clearance</h2>
                <dl>
                    <dt>Key signature</dt>
                    <dd><?= htmlspecialchars($loop['key']) ?></dd>
                    <dt>Tempo / meter</dt>
                    <dd><?= $loop['bpm'] ?> BPM, 4/4</dd>
                    <dt>Stems included</dt>
                    <dd><?= count($stems) ?> discrete channels</dd>
                    <dt>Licensing &amp; rights</dt>
                    <dd>100% royalty free</dd>
                </dl>
            </section>

            <section aria-labelledby="stems-heading">
                <h2 id="stems-heading">Stems breakdown inspector</h2>
                <a href="#">Download all (ZIP)</a>
                <ol>
                    <?php foreach ($stems as $stem): ?>
                        <li>
                            <h3><?= htmlspecialchars($stem['name']) ?></h3>
                            <p><?= htmlspecialchars($stem['details']) ?></p>
                            <button type="button">Mute</button>
                            <button type="button">Solo</button>
                            <button type="button">Download</button>
                        </li>
                    <?php endforeach; ?>
                </ol>
            </section>
        </article>

        <aside aria-labelledby="collab-heading">
            <h2 id="collab-heading">Request stems collab / topline</h2>
            <p>Send private remix stems, co-production proposals, or vocal topline directly to <?= htmlspecialchars($loop['producer']) ?> for joint release review.</p>
            <a href="/login">Request collaboration</a>
            <a href="/login">Instant fork</a>
        </aside>

        <section aria-labelledby="feedback-heading">
            <h2 id="feedback-heading">Session feedback (<?= count($comments) ?> notes)</h2>
            <form action="#" method="post">
                <label for="comment">Leave feedback</label>
                <textarea id="comment" name="comment" rows="3" placeholder="Add feedback or tie note at 01:14"></textarea>
                <button type="submit">Post</button>
            </form>

            <?php foreach ($comments as $comment): ?>
                <article>
                    <header>
                        <h3><?= htmlspecialchars($comment['author']) ?></h3>
                        <p><?= htmlspecialchars($comment['time']) ?> • at <?= htmlspecialchars($comment['marker']) ?></p>
                    </header>
                    <p><?= htmlspecialchars($comment['text']) ?></p>
                </article>
            <?php endforeach; ?>
        </section>
    </main>

    <?php require __DIR__ . '/partials/player.html.php'; ?>
</body>
</html>
