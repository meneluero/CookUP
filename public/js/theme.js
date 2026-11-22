// Przelacznik motywu jasny/ciemny.
// Poczatkowy motyw ustawia juz skrypt anty-FOUC w <head> (partials/head.html.php),
// tutaj obslugujemy tylko klikniecie i zmiane motywu systemowego.
// Widoczna ikone (ksiezyc/slonce) wybiera CSS na podstawie data-theme.
const storageKey = 'theme';
const themeToggle = document.querySelector('.theme-toggle');
const colorSchemeQuery = window.matchMedia('(prefers-color-scheme: dark)');

function getCurrentTheme() {
    return document.documentElement.dataset.theme === 'dark' ? 'dark' : 'light';
}

function getSavedTheme() {
    try {
        const theme = localStorage.getItem(storageKey);
        return theme === 'light' || theme === 'dark' ? theme : null;
    } catch (error) {
        return null;
    }
}

function saveTheme(theme) {
    try {
        localStorage.setItem(storageKey, theme);
    } catch (error) {
        // localStorage niedostepny - motyw dziala do konca biezacej strony
    }
}

function syncThemeToggle() {
    if (!themeToggle) {
        return;
    }

    const isDark = getCurrentTheme() === 'dark';
    themeToggle.setAttribute('aria-pressed', String(isDark));
    themeToggle.setAttribute('aria-label', isDark ? 'Włącz jasny motyw' : 'Włącz ciemny motyw');
}

function setTheme(theme, remember = true) {
    document.documentElement.dataset.theme = theme;

    if (remember) {
        saveTheme(theme);
    }

    syncThemeToggle();
}

themeToggle?.addEventListener('click', () => {
    setTheme(getCurrentTheme() === 'dark' ? 'light' : 'dark');
});

// zmiana motywu w systemie dziala tylko, dopoki uzytkownik sam nie wybral motywu
colorSchemeQuery.addEventListener('change', (event) => {
    if (getSavedTheme() !== null) {
        return;
    }

    setTheme(event.matches ? 'dark' : 'light', false);
});

syncThemeToggle();
