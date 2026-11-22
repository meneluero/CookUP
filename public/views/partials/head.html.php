<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($pageTitle) ?></title>
<!-- anty-FOUC: ustawia motyw przed zaladowaniem CSS, dlatego jest przed <link> i bez defer -->
<script>
    (function () {
        const storageKey = 'theme';
        let savedTheme = null;

        try {
            savedTheme = localStorage.getItem(storageKey);
        } catch (error) {
            savedTheme = null;
        }

        const hasSavedTheme = savedTheme === 'light' || savedTheme === 'dark';
        const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

        document.documentElement.dataset.theme = hasSavedTheme
            ? savedTheme
            : (systemPrefersDark ? 'dark' : 'light');
    })();
</script>
<link rel="stylesheet" href="/css/style.css">
<script src="/js/theme.js" defer></script>
