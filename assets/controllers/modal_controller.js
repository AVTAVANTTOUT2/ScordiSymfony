import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['dialog']

    open() {
        this.dialogTarget.classList.remove('hidden');
    }

    close() {
        this.dialogTarget.classList.add('hidden');
    }
}
