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
        <section aria-labelledby="pricing-heading">
            <p>Upgrade studio access // Tier selection</p>
            <h1 id="pricing-heading">Engineered for Serious Producers.</h1>
            <p>Choose the tier that fuels your production pipeline. High-precision stems, lossless 48kHz audio, and priority collaboration designed for peak performance.</p>

            <fieldset>
                <legend>Billing period</legend>
                <label><input type="radio" name="billing" value="monthly"> Monthly billing</label>
                <label><input type="radio" name="billing" value="yearly" checked> Yearly billing (save 20%)</label>
            </fieldset>
        </section>

        <section aria-labelledby="plans-heading">
            <h2 id="plans-heading">Plans</h2>
            <?php foreach ($plans as $plan): ?>
                <article>
                    <header>
                        <p><?= htmlspecialchars($plan['tier']) ?><?= $plan['highlighted'] ? ' • Most popular' : '' ?></p>
                        <h3><?= htmlspecialchars($plan['name']) ?></h3>
                        <p><data value="<?= htmlspecialchars($plan['price']) ?>"><?= htmlspecialchars($plan['price']) ?></data> / <?= htmlspecialchars($plan['period']) ?></p>
                        <p><small><?= htmlspecialchars($plan['note']) ?></small></p>
                    </header>
                    <p><?= htmlspecialchars($plan['description']) ?></p>
                    <ul>
                        <?php foreach ($plan['features'] as $feature): ?>
                            <li><?= htmlspecialchars($feature) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <?php if ($plan['highlighted']): ?>
                        <a href="/register"><?= htmlspecialchars($plan['cta']) ?></a>
                    <?php else: ?>
                        <button type="button" disabled><?= htmlspecialchars($plan['cta']) ?></button>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </section>

        <section aria-labelledby="spec-heading">
            <h2 id="spec-heading">Full system specification</h2>
            <table>
                <caption>Comparison of Starter and Pro Studio features</caption>
                <thead>
                    <tr>
                        <th scope="col">Core specification</th>
                        <th scope="col">Starter</th>
                        <th scope="col">Pro Studio</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($specification as $row): ?>
                        <tr>
                            <th scope="row"><?= htmlspecialchars($row['feature']) ?></th>
                            <td><?= htmlspecialchars($row['starter']) ?></td>
                            <td><?= htmlspecialchars($row['pro']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <section aria-labelledby="guarantees-heading">
            <h2 id="guarantees-heading">Guarantees</h2>
            <ul>
                <li><strong>30-day refund</strong> — zero risk. If it doesn't level up your pipeline, cancel immediately.</li>
                <li><strong>Instant PDF license</strong> — generated immediately with custom project metadata upon download.</li>
                <li><strong>Direct stems ZIP</strong> — organized stem folders with BPM and root key metadata embedded.</li>
                <li><strong>Cancel anytime</strong> — keep all stems and master rights forever, even after cancelation.</li>
            </ul>
        </section>

        <section aria-labelledby="faq-heading">
            <h2 id="faq-heading">Operational questions</h2>
            <?php foreach ($faq as $item): ?>
                <details>
                    <summary><?= htmlspecialchars($item['question']) ?></summary>
                    <p><?= htmlspecialchars($item['answer']) ?></p>
                </details>
            <?php endforeach; ?>
        </section>
    </main>

    <?php require __DIR__ . '/partials/player.html.php'; ?>
</body>
</html>
