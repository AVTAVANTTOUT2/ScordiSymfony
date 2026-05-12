import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        this.timeout = window.setTimeout(() => {
            this.element.remove();
        }, 4000);
    }

    disconnect() {
        window.clearTimeout(this.timeout);
    }
}
