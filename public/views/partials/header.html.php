<?php
$activeNav = $activeNav ?? '';
$navItems = [
    'home' => ['label' => 'Home', 'href' => '/'],
    'loops' => ['label' => 'Loops', 'href' => '/loops'],
    'profile' => ['label' => 'Profile', 'href' => '/profile'],
    'pricing' => ['label' => 'Pricing', 'href' => '/pricing'],
];
?>
<header>
    <a href="/">
        <strong>CookUP+</strong>
        <small>Studio workstation v2.4</small>
    </a>

    <form action="/loops" method="get" role="search">
        <label for="site-search">Search loops</label>
        <input type="search" id="site-search" name="q" placeholder="Search loops, one-shots, stem packs...">
        <button type="submit">Search</button>
    </form>

    <nav aria-label="Main navigation">
        <ul>
            <?php foreach ($navItems as $key => $item): ?>
                <li>
                    <a href="<?= $item['href'] ?>"<?= $key === $activeNav ? ' aria-current="page"' : '' ?>>
                        <?= htmlspecialchars($item['label']) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>

    <a href="/login">Sign in</a>
</header>
