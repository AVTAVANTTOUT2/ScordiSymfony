import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['panel', 'backdrop'];

    connect() {
        this.close();
    }

    open() {
        this.panelTarget.classList.remove('-translate-x-full');
        this.backdropTarget.classList.remove('hidden');
    }

    close() {
        this.panelTarget.classList.add('-translate-x-full');
        this.backdropTarget.classList.add('hidden');
    }
}
