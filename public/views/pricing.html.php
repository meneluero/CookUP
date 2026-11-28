<!doctype html>
<html lang="en">
<head>
    <?php require __DIR__ . '/partials/head.html.php'; ?>
</head>
<body>
    <?php require __DIR__ . '/partials/header.html.php'; ?>

    <main class="page">
        <div class="page-intro">
            <h1>Pick your plan</h1>
            <p>Start for free. Switch to Pro when you need more uploads and lossless downloads.</p>
        </div>

        <?php if ($notice !== null): ?>
            <p role="status" class="notice"><?= htmlspecialchars($notice) ?></p>
        <?php endif; ?>

        <section aria-labelledby="plans-heading">
            <h2 id="plans-heading">Plans</h2>
            <div class="plans">
                <?php foreach ($plans as $plan): ?>
                    <article class="panel plan<?= $plan['highlighted'] ? ' plan--highlighted' : '' ?>">
                        <header class="plan-header">
                            <?php if ($plan['highlighted']): ?>
                                <p class="plan-badge">Most popular</p>
                            <?php endif; ?>
                            <h3><?= htmlspecialchars($plan['name']) ?></h3>
                            <p class="plan-price"><strong><?= htmlspecialchars($plan['price']) ?></strong> / month</p>
                            <p class="plan-note"><?= htmlspecialchars($plan['note']) ?></p>
                        </header>

                        <ul class="plan-features">
                            <?php foreach ($plan['features'] as $feature): ?>
                                <li><?= htmlspecialchars($feature) ?></li>
                            <?php endforeach; ?>
                        </ul>

                        <?php $cta = $plan['cta']; ?>
                        <?php if ($cta['type'] === 'link'): ?>
                            <a href="<?= htmlspecialchars($cta['href']) ?>" class="button<?= $plan['highlighted'] ? ' button--primary' : '' ?>"><?= htmlspecialchars($cta['label']) ?></a>
                        <?php elseif ($cta['type'] === 'upgrade'): ?>
                            <form action="/pricing/upgrade" method="post">
                                <input type="hidden" name="csrfToken" value="<?= htmlspecialchars($csrfToken) ?>">
                                <button type="submit" class="button button--primary"><?= htmlspecialchars($cta['label']) ?></button>
                            </form>
                        <?php else: ?>
                            <p class="plan-current"><?= htmlspecialchars($cta['label']) ?></p>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

        <section aria-labelledby="compare-heading">
            <h2 id="compare-heading">Compare plans</h2>
            <table class="compare-table">
                <caption class="visually-hidden">Starter and Pro features side by side</caption>
                <thead>
                    <tr>
                        <th scope="col">Feature</th>
                        <th scope="col">Starter</th>
                        <th scope="col">Pro</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($comparison as $row): ?>
                        <tr>
                            <th scope="row"><?= htmlspecialchars($row['feature']) ?></th>
                            <td><?= htmlspecialchars($row['starter']) ?></td>
                            <td><?= htmlspecialchars($row['pro']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>

    <?php require __DIR__ . '/partials/player.html.php'; ?>
</body>
</html>
