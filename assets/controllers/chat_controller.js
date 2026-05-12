import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static values = {
        pollUrl: String,
        historyUrl: String,
        postUrl: String,
    }

    static targets = ['list', 'form', 'content', 'anchor', 'newBanner']

    connect() {
        this.lastTimestamp = Math.floor(Date.now() / 1000);
        this.oldestMessageId = null;
        this.loadingHistory = false;
        this.unseenCount = 0;
        this.startPolling();
        this.bindScroll();
    }

    disconnect() {
        if (this.intervalId) {
            clearInterval(this.intervalId);
        }
    }

    startPolling() {
        this.intervalId = setInterval(() => this.poll(), 2000);
    }

    bindScroll() {
        if (!this.hasListTarget) {
            return;
        }
        this.listTarget.addEventListener('scroll', () => {
            if (this.listTarget.scrollTop < 60) {
                this.loadHistory();
            }
        });
    }

    async submit(event) {
        event.preventDefault();
        const formData = new FormData(this.formTarget);
        const content = (formData.get('content') || '').toString().trim();
        if (!content) {
            return;
        }

        await fetch(this.postUrlValue, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
        });

        this.contentTarget.value = '';
        this.poll(true);
    }

    async poll(force = false) {
        if (!this.pollUrlValue) {
            return;
        }

        const url = new URL(this.pollUrlValue, window.location.origin);
        url.searchParams.set('since', String(this.lastTimestamp));

        const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        if (!response.ok) {
            return;
        }

        const payload = await response.json();
        if (!payload.messages || payload.messages.length === 0) {
            return;
        }

        const isAtBottom = this.listTarget.scrollHeight - this.listTarget.clientHeight - this.listTarget.scrollTop < 80;
        payload.messages.forEach((message) => {
            this.listTarget.insertAdjacentHTML('beforeend', this.renderMessage(message));
            this.lastTimestamp = Math.max(this.lastTimestamp, Number(message.createdAtTs || 0));
            if (this.oldestMessageId === null || Number(message.id) < Number(this.oldestMessageId)) {
                this.oldestMessageId = Number(message.id);
            }
        });

        if (force || isAtBottom) {
            this.unseenCount = 0;
            this.updateNewBanner();
            this.scrollToBottom();
        } else {
            this.unseenCount += payload.messages.length;
            this.updateNewBanner();
        }
    }

    async loadHistory() {
        if (this.loadingHistory || !this.historyUrlValue || this.oldestMessageId === null) {
            return;
        }

        this.loadingHistory = true;
        const currentHeight = this.listTarget.scrollHeight;
        const url = new URL(this.historyUrlValue, window.location.origin);
        url.searchParams.set('before', String(this.oldestMessageId));

        const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        if (response.ok) {
            const payload = await response.json();
            if (payload.messages && payload.messages.length > 0) {
                payload.messages.forEach((message) => {
                    this.listTarget.insertAdjacentHTML('afterbegin', this.renderMessage(message));
                    this.oldestMessageId = Number(message.id);
                });
                const newHeight = this.listTarget.scrollHeight;
                this.listTarget.scrollTop = newHeight - currentHeight;
            }
        }

        this.loadingHistory = false;
    }

    scrollToBottom() {
        this.anchorTarget.scrollIntoView({ behavior: 'smooth', block: 'end' });
    }

    jumpToBottom() {
        this.unseenCount = 0;
        this.updateNewBanner();
        this.scrollToBottom();
    }

    updateNewBanner() {
        if (!this.hasNewBannerTarget) {
            return;
        }
        if (this.unseenCount <= 0) {
            this.newBannerTarget.classList.add('hidden');
            return;
        }
        this.newBannerTarget.textContent = `↓ ${this.unseenCount} nouveaux messages`;
        this.newBannerTarget.classList.remove('hidden');
    }

    renderMessage(message) {
        const username = message.author?.username || 'unknown';
        const presence = message.author?.presence || 'offline';
        const date = new Date(message.createdAt).toLocaleTimeString();

        return `
            <article class="message-row animate-message-in" data-message-id="${message.id}">
                <header class="mb-1 flex items-center gap-2 text-sm">
                    <span class="font-semibold text-indigo-300">${username}</span>
                    <span class="inline-block size-2 rounded-full ${presence === 'online' ? 'bg-green-400' : (presence === 'idle' ? 'bg-yellow-400' : 'bg-zinc-500')}"></span>
                    <time class="text-zinc-400">${date}</time>
                </header>
                <div class="prose prose-invert max-w-none">${message.contentHtml}</div>
                <div class="message-toolbar">
                    <button type="button" class="ui-icon-btn" aria-label="Réagir">🙂</button>
                    <button type="button" class="ui-icon-btn" aria-label="Répondre">↩</button>
                </div>
            </article>
        `;
    }
}
