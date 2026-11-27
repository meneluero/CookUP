// Walidacja formularzy w przeglądarce - tylko dla wygody, serwer i tak sprawdza wszystko jeszcze raz.
// Pole z data-match="id" musi mieć tę samą wartość co pole o tym id (np. powtórzenie hasła).
// Tekst błędu podaje PHP w data-mismatch-message, więc tłumaczenia zostają po stronie serwera.
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
