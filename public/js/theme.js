(() => {
    const toggle = document.getElementById('themeToggle');
    const root = document.documentElement;

    if (!toggle) return;

    const updateTheme = (theme) => {
        const isDark = theme === 'dark';
        root.dataset.theme = theme;
        root.dataset.bsTheme = theme;
        toggle.setAttribute('aria-pressed', String(isDark));
        toggle.setAttribute('aria-label', `Alternar para tema ${isDark ? 'claro' : 'escuro'}`);
        toggle.querySelector('i').className = `bi ${isDark ? 'bi-sun' : 'bi-moon-stars'}`;
        toggle.querySelector('span').textContent = `Tema ${isDark ? 'claro' : 'escuro'}`;
    };

    updateTheme(root.dataset.theme === 'dark' ? 'dark' : 'light');

    toggle.addEventListener('click', () => {
        const nextTheme = root.dataset.theme === 'dark' ? 'light' : 'dark';
        updateTheme(nextTheme);

        try {
            localStorage.setItem('deblix-theme', nextTheme);
        } catch (error) {
            // A escolha vale durante esta página se o armazenamento estiver bloqueado.
        }
    });
})();
