<!doctype html>
<html lang="en">
<head>
    <?php require __DIR__ . '/partials/head.html.php'; ?>
</head>
<body>
    <?php require __DIR__ . '/partials/header.html.php'; ?>

    <main class="page">
        <div class="page-intro">
            <h1>Admin panel</h1>
            <p>Change roles, remove accounts and take down loops.</p>
        </div>

        <?php if ($notice !== null): ?>
            <p role="status" class="notice"><?= htmlspecialchars($notice) ?></p>
        <?php endif; ?>

        <section aria-labelledby="admin-users-heading">
            <h2 id="admin-users-heading">Users (<?= count($users) ?>)</h2>
            <ul class="row-list">
                <?php foreach ($users as $account): ?>
                    <?php $name = htmlspecialchars($account->getName()); ?>
                    <li class="row-card">
                        <div>
                            <h3><?= $name ?></h3>
                            <p class="row-meta">
                                <?= htmlspecialchars($account->getEmail()) ?> •
                                joined <time datetime="<?= $account->getCreatedAt()->format('Y-m-d') ?>"><?= $account->getCreatedAt()->format('j M Y') ?></time>
                            </p>
                        </div>

                        <?php if ($account->getId() === $adminId): ?>
                            <p class="row-note">Your account (<?= htmlspecialchars($account->getRole()) ?>)</p>
                        <?php else: ?>
                            <div class="row-actions">
                                <form action="/admin/users/<?= $account->getId() ?>/role" method="post" class="inline-form">
                                    <input type="hidden" name="csrfToken" value="<?= htmlspecialchars($csrfToken) ?>">
                                    <label for="role-<?= $account->getId() ?>" class="visually-hidden">Role of <?= $name ?></label>
                                    <select id="role-<?= $account->getId() ?>" name="role">
                                        <?php foreach ($roles as $role): ?>
                                            <option value="<?= $role ?>"<?= $role === $account->getRole() ? ' selected' : '' ?>><?= ucfirst($role) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="submit" class="button">Save<span class="visually-hidden"> role of <?= $name ?></span></button>
                                </form>
                                <form action="/admin/users/<?= $account->getId() ?>/delete" method="post"
                                      data-confirm="Delete the account of <?= $name ?>? This cannot be undone.">
                                    <input type="hidden" name="csrfToken" value="<?= htmlspecialchars($csrfToken) ?>">
                                    <button type="submit" class="button button--danger">Delete<span class="visually-hidden"> <?= $name ?></span></button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>

        <section aria-labelledby="admin-loops-heading">
            <h2 id="admin-loops-heading">Loops (<?= count($loops) ?>)</h2>
            <?php if ($loops === []): ?>
                <p class="row-note">No loops yet.</p>
            <?php else: ?>
                <ul class="row-list">
                    <?php foreach ($loops as $loop): ?>
                        <?php $title = htmlspecialchars($loop->getTitle()); ?>
                        <li class="row-card">
                            <div>
                                <h3><a href="/loops/<?= $loop->getId() ?>"><?= $title ?></a></h3>
                                <p class="row-meta">
                                    prod. <?= htmlspecialchars($loop->getProducer()) ?> •
                                    <?= htmlspecialchars($loop->getGenre()) ?> •
                                    added <time datetime="<?= $loop->getCreatedAt()?->format('Y-m-d') ?>"><?= $loop->getCreatedAt()?->format('j M Y') ?></time>
                                </p>
                            </div>
                            <form action="/admin/loops/<?= $loop->getId() ?>/delete" method="post"
                                  data-confirm="Delete the loop &quot;<?= $title ?>&quot;? This cannot be undone.">
                                <input type="hidden" name="csrfToken" value="<?= htmlspecialchars($csrfToken) ?>">
                                <button type="submit" class="button button--danger">Delete<span class="visually-hidden"> <?= $title ?></span></button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </section>
    </main>

    <?php require __DIR__ . '/partials/player.html.php'; ?>
</body>
</html>
