// Zachowanie formularzy w przeglądarce - tylko dla wygody, serwer i tak sprawdza wszystko jeszcze raz.
// Teksty komunikatów podaje PHP w atrybutach data-, więc tłumaczenia zostają po stronie serwera.

// Pole z data-match="id" musi mieć tę samą wartość co pole o tym id (np. powtórzenie hasła).
document.querySelectorAll('input[data-match]').forEach((confirmation) => {
    const original = document.getElementById(confirmation.dataset.match);

    if (!original) {
        return;
    }

    function validateMatch() {
        // puste pole zostawiamy atrybutowi required, żeby pokazał swój komunikat
        const isMismatch = confirmation.value !== '' && confirmation.value !== original.value;
        confirmation.setCustomValidity(isMismatch ? confirmation.dataset.mismatchMessage : '');
    }

    original.addEventListener('input', validateMatch);
    confirmation.addEventListener('input', validateMatch);
});

// Formularz z data-confirm="pytanie" pyta przed wysłaniem (np. usuwanie w panelu admina).
document.querySelectorAll('form[data-confirm]').forEach((form) => {
    form.addEventListener('submit', (event) => {
        if (!window.confirm(form.dataset.confirm)) {
            event.preventDefault();
        }
    });
});
