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
        <section aria-labelledby="producer-heading">
            <!-- TODO: zdjęcie profilowe producenta (<img>) po dodaniu uploadu plików -->
            <h1 id="producer-heading"><?= htmlspecialchars($producer['name']) ?></h1>
            <p><?= htmlspecialchars($producer['handle']) ?> • <?= htmlspecialchars($producer['role']) ?></p>
            <p><?= htmlspecialchars($producer['bio']) ?></p>
            <ul>
                <li>DAW: <?= htmlspecialchars($producer['daw']) ?></li>
                <li>Master bus: <?= htmlspecialchars($producer['masterBus']) ?></li>
                <li>Active since <?= htmlspecialchars($producer['activeSince']) ?></li>
            </ul>
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
                <caption>Loops published by <?= htmlspecialchars($producer['name']) ?></caption>
                <thead>
                    <tr>
                        <th scope="col">Track &amp; stem title</th>
                        <th scope="col">Technical profile</th>
                        <th scope="col">Plays</th>
                        <th scope="col">Status</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($loops as $loop): ?>
                        <tr>
                            <th scope="row"><a href="/loops/<?= $loop['id'] ?>"><?= htmlspecialchars($loop['title']) ?></a></th>
                            <td><?= $loop['bpm'] ?> BPM • <?= htmlspecialchars($loop['key']) ?></td>
                            <td><?= htmlspecialchars($loop['plays']) ?></td>
                            <td><?= htmlspecialchars($loop['status']) ?></td>
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
