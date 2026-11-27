<header class="site-header">
    <a href="/" class="logo">
        <strong>CookUP+</strong>
        <small>Studio workstation v2.4</small>
    </a>

    <form action="/loops" method="get" role="search" class="search">
        <label for="site-search" class="visually-hidden">Search loops</label>
        <input type="search" id="site-search" name="q" placeholder="Search loops, one-shots, stem packs...">
        <button type="submit" class="button">Search</button>
    </form>

    <?php require __DIR__ . '/nav.html.php'; ?>

    <?php if ($currentUserName !== null): ?>
        <p class="current-user">Signed in as <strong><?= htmlspecialchars($currentUserName) ?></strong></p>
        <!-- wylogowanie zmienia stan, więc to formularz POST z tokenem CSRF, a nie zwykły link -->
        <form action="/logout" method="post">
            <input type="hidden" name="csrfToken" value="<?= htmlspecialchars($csrfToken) ?>">
            <button type="submit" class="button">Log out</button>
        </form>
    <?php else: ?>
        <a href="/login" class="button button--primary">Sign in</a>
        <a href="/register" class="button">Register</a>
    <?php endif; ?>
</header>
