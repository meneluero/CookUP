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

    <!-- aria-label i aria-pressed aktualizuje public/js/theme.js -->
    <button class="theme-toggle" type="button" aria-label="Włącz ciemny motyw" aria-pressed="false" title="Zmień motyw">
        <svg class="icon-moon" aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"/>
        </svg>
        <svg class="icon-sun" aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <circle cx="12" cy="12" r="4"/>
            <path d="M12 2v2M12 20v2M2 12h2M20 12h2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/>
        </svg>
    </button>
</nav>
