<!doctype html>
<html lang="en">
<head>
    <?php require __DIR__ . '/partials/head.html.php'; ?>
</head>
<body>
    <?php require __DIR__ . '/partials/header.html.php'; ?>

    <main>
        <section aria-labelledby="producer-heading">
            <!-- TODO: zdjęcie profilowe producenta (<img>) po dodaniu uploadu plików -->
            <h1 id="producer-heading"><?= htmlspecialchars($user->getName()) ?></h1>
            <p><?= htmlspecialchars(ucfirst($user->getRole())) ?> account</p>
            <p>Member since <time datetime="<?= $user->getCreatedAt()->format('Y-m-d') ?>"><?= $user->getCreatedAt()->format('F Y') ?></time></p>
            <?php if ($user->isAdmin()): ?>
                <a href="/admin" class="button button--primary">Open admin panel</a>
            <?php elseif ($user->isStarter()): ?>
                <a href="/pricing" class="button button--primary">Upgrade to Pro</a>
            <?php endif; ?>
            <a href="#">Edit profile</a>
            <a href="#">Producer card</a>
        </section>

        <section aria-labelledby="producer-stats-heading">
            <h2 id="producer-stats-heading">Statistics</h2>
            <dl>
                <?php foreach ($stats as $stat): ?>
                    <div>
                        <dt><?= htmlspecialchars($stat['label']) ?></dt>
                        <dd><?= htmlspecialchars($stat['value']) ?> <small><?= htmlspecialchars($stat['note']) ?></small></dd>
                    </div>
                <?php endforeach; ?>
            </dl>
        </section>

        <section aria-labelledby="my-loops-heading">
            <h2 id="my-loops-heading">My loops</h2>
            <nav aria-label="Profile sections">
                <a href="#my-loops-heading" aria-current="page">My loops (<?= count($loops) ?>)</a>
                <a href="#">Favorites</a>
                <a href="#">Drafts</a>
                <a href="#">Analytics</a>
            </nav>
            <a href="#">Upload new loop</a>

            <form action="/profile" method="get" role="search">
                <label for="catalog-search">Search my catalog by title, key or tag</label>
                <input type="search" id="catalog-search" name="q">
                <button type="submit">Search</button>
            </form>

            <table>
                <caption>Loops published by <?= htmlspecialchars($user->getName()) ?></caption>
                <thead>
                    <tr>
                        <th scope="col">Track &amp; stem title</th>
                        <th scope="col">Technical profile</th>
                        <th scope="col">Genre</th>
                        <th scope="col">Length</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($loops as $loop): ?>
                        <tr>
                            <th scope="row"><a href="/loops/<?= $loop->getId() ?>"><?= htmlspecialchars($loop->getTitle()) ?></a></th>
                            <td><?= $loop->getBpm() ?> BPM • <?= htmlspecialchars($loop->getMusicalKey()) ?></td>
                            <td><?= htmlspecialchars($loop->getGenre()) ?></td>
                            <td><time datetime="PT<?= $loop->getDurationSeconds() ?>S"><?= $loop->getFormattedDuration() ?></time></td>
                            <td>
                                <a href="#">Edit</a>
                                <a href="#">Download</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <section aria-labelledby="upload-heading">
            <h2 id="upload-heading">Quick multi-track stem import</h2>
            <form action="#" method="post" enctype="multipart/form-data">
                <label for="stems">Drop multi-track stems or ZIP (.wav, .mid up to 2GB)</label>
                <input type="file" id="stems" name="stems[]" accept=".wav,.mid,.zip" multiple>
                <button type="submit">Upload</button>
            </form>
        </section>
    </main>

    <?php require __DIR__ . '/partials/player.html.php'; ?>
</body>
</html>
