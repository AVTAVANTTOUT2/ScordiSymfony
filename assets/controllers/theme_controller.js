import { Controller } from '@hotwired/stimulus';

const STORAGE_KEY = 'discord_clone_theme';

export default class extends Controller {
    static targets = ['label']

    connect() {
        this.applyTheme(this.currentTheme());
    }

    toggle() {
        const current = this.currentTheme();
        this.applyTheme(current === 'dark' ? 'light' : 'dark');
    }

    currentTheme() {
        if (document.documentElement.classList.contains('dark')) {
            return 'dark';
        }

        return localStorage.getItem(STORAGE_KEY) || 'dark';
    }

    applyTheme(theme) {
        document.documentElement.classList.toggle('dark', theme === 'dark');
        localStorage.setItem(STORAGE_KEY, theme);
        if (this.hasLabelTarget) {
            this.labelTarget.textContent = theme === 'dark' ? 'Mode clair' : 'Mode sombre';
        }
    }
}
