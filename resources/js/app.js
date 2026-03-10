import './bootstrap';

import Alpine from 'alpinejs';
import { init as turboInit } from './turbo-navigation';

window.Alpine = Alpine;

Alpine.start();

// Initialize PJAX navigation after DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', turboInit);
} else {
    turboInit();
}

const NOTIFICATION_POLL_INTERVAL_MS = 10000;
let notificationPollTimer = null;
let notificationPollInFlight = false;

function formatNotificationCount(count) {
    return count > 99 ? '99+' : String(count);
}

function updateNotificationBadges(unreadCount) {
    document.querySelectorAll('[data-notification-count]').forEach((badge) => {
        badge.textContent = formatNotificationCount(unreadCount);
        badge.classList.toggle('hidden', unreadCount <= 0);
    });
}

function isFirstNotificationsPage() {
    const feed = document.querySelector('[data-notification-feed]');
    if (!feed) return false;

    const params = new URLSearchParams(window.location.search);
    const page = params.get('page');
    return !page || page === '1';
}

async function pollNotifications() {
    const pollUrl = document.querySelector('meta[name="notifications-poll-url"]')?.content;
    if (!pollUrl || notificationPollInFlight) return;

    notificationPollInFlight = true;

    try {
        const response = await fetch(pollUrl, {
            method: 'GET',
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        });

        if (!response.ok) return;

        const payload = await response.json();
        const unreadCount = Number(payload.unreadCount ?? 0);
        updateNotificationBadges(unreadCount);

        if (isFirstNotificationsPage() && typeof payload.itemsHtml === 'string') {
            const feed = document.querySelector('[data-notification-feed]');
            if (feed) {
                feed.innerHTML = payload.itemsHtml;
            }
        }
    } catch (_) {
        // Ignore transient poll failures; next interval will retry.
    } finally {
        notificationPollInFlight = false;
    }
}

function initNotificationPolling() {
    const pollUrl = document.querySelector('meta[name="notifications-poll-url"]')?.content;

    if (!pollUrl) {
        if (notificationPollTimer) {
            clearInterval(notificationPollTimer);
            notificationPollTimer = null;
        }
        return;
    }

    if (!notificationPollTimer) {
        notificationPollTimer = setInterval(pollNotifications, NOTIFICATION_POLL_INTERVAL_MS);
    }

    pollNotifications();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initNotificationPolling);
} else {
    initNotificationPolling();
}

document.addEventListener('visibilitychange', () => {
    if (document.visibilityState === 'visible') {
        pollNotifications();
    }
});
