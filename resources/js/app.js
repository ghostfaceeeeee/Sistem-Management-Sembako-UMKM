import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

const applyTheme = (theme) => {
    const normalized = theme === 'light' ? 'light' : 'dark';
    document.documentElement.classList.remove('theme-dark', 'theme-light');
    document.documentElement.classList.add(`theme-${normalized}`);
    localStorage.setItem('app-theme', normalized);
};

window.setAppTheme = applyTheme;

const initialTheme = localStorage.getItem('app-theme') === 'light' ? 'light' : 'dark';
applyTheme(initialTheme);
