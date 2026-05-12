import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['content'];

    show() {
        if (this.hasContentTarget) {
            this.contentTarget.classList.remove('opacity-0', 'pointer-events-none');
        }
    }

    hide() {
        if (this.hasContentTarget) {
            this.contentTarget.classList.add('opacity-0', 'pointer-events-none');
        }
    }
}
