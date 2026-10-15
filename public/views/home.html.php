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
        <section aria-labelledby="vault-heading">
            <p>Precision vault // Grand R&B</p>
            <h1 id="vault-heading">Loops &amp; Sample Vault</h1>
            <p>Curated zero-latency studio stems, master loops &amp; MIDI compositions engineered for modern hitmakers.</p>
            <a href="/loops">Browse all loops</a>
            <a href="/profile">Upload loop</a>
        </section>

        <section aria-labelledby="stats-heading">
            <h2 id="stats-heading">Vault statistics</h2>
            <dl>
                <?php foreach ($stats as $stat): ?>
                    <div>
                        <dt><?= htmlspecialchars($stat['label']) ?></dt>
                        <dd><?= htmlspecialchars($stat['value']) ?> <small><?= htmlspecialchars($stat['note']) ?></small></dd>
                    </div>
                <?php endforeach; ?>
            </dl>
        </section>

        <section aria-labelledby="latest-heading">
            <h2 id="latest-heading">Latest drops</h2>
            <table>
                <caption>Latest loops uploaded by producers</caption>
                <thead>
                    <tr>
                        <th scope="col">Track title / producer</th>
                        <th scope="col">BPM</th>
                        <th scope="col">Key</th>
                        <th scope="col">Length</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($loops as $loop): ?>
                        <tr>
                            <th scope="row">
                                <a href="/loops/<?= $loop['id'] ?>"><?= htmlspecialchars($loop['title']) ?></a>
                                <small><?= htmlspecialchars($loop['producer']) ?> • <?= htmlspecialchars($loop['genre']) ?></small>
                            </th>
                            <td><?= $loop['bpm'] ?></td>
                            <td><?= htmlspecialchars($loop['key']) ?></td>
                            <td><?= $loop['bars'] ?> bars (<?= htmlspecialchars($loop['duration']) ?>)</td>
                            <td><a href="/loops/<?= $loop['id'] ?>">Stems</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <section aria-labelledby="collections-heading">
            <h2 id="collections-heading">Featured collections</h2>
            <?php foreach ($collections as $collection): ?>
                <article>
                    <p><?= htmlspecialchars($collection['label']) ?></p>
                    <h3><?= htmlspecialchars($collection['title']) ?></h3>
                    <p><?= htmlspecialchars($collection['details']) ?></p>
                </article>
            <?php endforeach; ?>
        </section>
    </main>

    <?php require __DIR__ . '/partials/player.html.php'; ?>
</body>
</html>
