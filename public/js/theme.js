(() => {
    const root = document.documentElement;
    const choices = Array.from(document.querySelectorAll('[data-theme-choice]'));

    if (choices.length === 0) return;

    const applyTheme = (theme) => {
        if (theme !== 'light' && theme !== 'dark') return;

        root.dataset.theme = theme;
        root.dataset.bsTheme = theme;

        choices.forEach((choice) => {
            choice.setAttribute('aria-pressed', String(choice.dataset.themeChoice === theme));
        });
    };

    applyTheme(root.dataset.theme === 'dark' ? 'dark' : 'light');

    choices.forEach((choice) => {
        choice.addEventListener('click', () => {
            const theme = choice.dataset.themeChoice;
            applyTheme(theme);

            try {
                localStorage.setItem('deblix-theme', theme);
            } catch (error) {
                // A preferência continua aplicada até a página ser recarregada.
            }
        });
    });
})();
