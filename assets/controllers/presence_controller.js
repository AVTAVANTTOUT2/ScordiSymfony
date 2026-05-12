import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static values = {
        heartbeatUrl: String,
    }

    connect() {
        this.sendHeartbeat();
        this.intervalId = setInterval(() => this.sendHeartbeat(), 30000);
    }

    disconnect() {
        if (this.intervalId) {
            clearInterval(this.intervalId);
        }
    }

    async sendHeartbeat() {
        if (!this.heartbeatUrlValue) {
            return;
        }

        await fetch(this.heartbeatUrlValue, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({ status: 'online' }),
        });
    }
}
