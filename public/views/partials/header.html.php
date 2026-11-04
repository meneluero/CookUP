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

    <a href="/login" class="button button--primary">Sign in</a>
    <a href="/register" class="button">Register</a>
</header>
