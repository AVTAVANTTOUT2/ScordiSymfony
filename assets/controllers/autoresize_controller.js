import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['input', 'submit'];

    connect() {
        this.resize();
        this.toggleSubmit();
    }

    onInput() {
        this.resize();
        this.toggleSubmit();
    }

    resize() {
        const el = this.inputTarget;
        el.style.height = 'auto';
        const max = 8 * 24;
        el.style.height = `${Math.min(el.scrollHeight, max)}px`;
        el.style.overflowY = el.scrollHeight > max ? 'auto' : 'hidden';
    }

    toggleSubmit() {
        if (!this.hasSubmitTarget) {
            return;
        }

        this.submitTarget.disabled = this.inputTarget.value.trim().length === 0;
    }
}
