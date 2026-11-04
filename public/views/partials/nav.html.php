<?php
// komponent nawigacji - jeden plik dolaczany do kazdego widoku,
// wiec nowy link dodajemy tylko tutaj
$activeNav = $activeNav ?? '';
$navItems = [
    'home' => ['label' => 'Home', 'href' => '/'],
    'loops' => ['label' => 'Loops', 'href' => '/loops'],
    'profile' => ['label' => 'Profile', 'href' => '/profile'],
    'pricing' => ['label' => 'Pricing', 'href' => '/pricing'],
];
?>
<nav aria-label="Main navigation" class="main-nav">
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
