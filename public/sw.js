// Rentify Service Worker
// Provides offline support with cache-first for static assets and network-first for pages

const APP_VERSION = '1.0.0';
const CACHE_NAME = `rentify-cache-v${APP_VERSION}`;
const OFFLINE_URL = '/offline';

// App shell resources to pre-cache on install
const APP_SHELL = [
    '/',
    '/offline',
    '/manifest.json',
    '/favicon.ico',
];

// Dashboard routes to attempt caching when visited
const CACHEABLE_PAGES = [
    '/dashboard',
    '/agent/dashboard',
    '/landlord/dashboard',
    '/tenant/dashboard',
];

// ============================================================
// INSTALL - Pre-cache the app shell
// ============================================================
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then((cache) => {
                console.log('[SW] Pre-caching app shell');
                // Use addAll with individual catches so one failure doesn't block all
                return Promise.allSettled(
                    APP_SHELL.map((url) =>
                        cache.add(url).catch((err) => {
                            console.warn(`[SW] Failed to cache: ${url}`, err);
                        })
                    )
                );
            })
            .then(() => {
                // Force this SW to become the active SW immediately
                return self.skipWaiting();
            })
    );
});

// ============================================================
// ACTIVATE - Clean up old caches
// ============================================================
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys()
            .then((cacheNames) => {
                return Promise.all(
                    cacheNames
                        .filter((name) => name.startsWith('rentify-cache-') && name !== CACHE_NAME)
                        .map((name) => {
                            console.log(`[SW] Deleting old cache: ${name}`);
                            return caches.delete(name);
                        })
                );
            })
            .then(() => {
                // Take control of all clients immediately
                return self.clients.claim();
            })
    );
});

// ============================================================
// FETCH - Serve from cache or network
// ============================================================
self.addEventListener('fetch', (event) => {
    const { request } = event;
    const url = new URL(request.url);

    // Only handle GET requests
    if (request.method !== 'GET') {
        return;
    }

    // Skip cross-origin requests (except fonts from bunny.net)
    if (url.origin !== self.location.origin && !url.hostname.includes('fonts.bunny.net')) {
        return;
    }

    // Determine the strategy based on request type
    if (isStaticAsset(url)) {
        // Cache-first for static assets (CSS, JS, images, fonts)
        event.respondWith(cacheFirst(request));
    } else if (isNavigationRequest(request)) {
        // Network-first for HTML pages
        event.respondWith(networkFirstForPage(request));
    } else {
        // Network-first for API calls and other requests
        event.respondWith(networkFirst(request));
    }
});

// ============================================================
// STRATEGIES
// ============================================================

/**
 * Cache-first strategy: try cache, fallback to network (and update cache).
 * Best for static assets that change infrequently.
 */
async function cacheFirst(request) {
    const cached = await caches.match(request);
    if (cached) {
        return cached;
    }

    try {
        const response = await fetch(request);
        if (response.ok) {
            const cache = await caches.open(CACHE_NAME);
            cache.put(request, response.clone());
        }
        return response;
    } catch (error) {
        // For images, return a transparent 1px gif as fallback
        if (isImageRequest(request)) {
            return new Response(
                'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7',
                { headers: { 'Content-Type': 'image/gif' } }
            );
        }
        return new Response('Offline', { status: 503, statusText: 'Service Unavailable' });
    }
}

/**
 * Network-first strategy for pages: try network, fallback to cache, then offline page.
 */
async function networkFirstForPage(request) {
    try {
        const response = await fetch(request);
        if (response.ok) {
            const cache = await caches.open(CACHE_NAME);
            cache.put(request, response.clone());
        }
        return response;
    } catch (error) {
        // Try to serve from cache
        const cached = await caches.match(request);
        if (cached) {
            return cached;
        }

        // Serve the offline fallback page
        const offlinePage = await caches.match(OFFLINE_URL);
        if (offlinePage) {
            return offlinePage;
        }

        // Last resort: return a basic offline response
        return new Response(
            '<html><body><h1>You are offline</h1><p>Please check your connection and try again.</p></body></html>',
            { headers: { 'Content-Type': 'text/html' }, status: 503 }
        );
    }
}

/**
 * Network-first strategy: try network, fallback to cache.
 * Best for dynamic content.
 */
async function networkFirst(request) {
    try {
        const response = await fetch(request);
        if (response.ok) {
            const cache = await caches.open(CACHE_NAME);
            cache.put(request, response.clone());
        }
        return response;
    } catch (error) {
        const cached = await caches.match(request);
        if (cached) {
            return cached;
        }
        return new Response('Offline', { status: 503, statusText: 'Service Unavailable' });
    }
}

// ============================================================
// HELPERS
// ============================================================

/**
 * Check if a URL points to a static asset.
 */
function isStaticAsset(url) {
    const staticExtensions = [
        '.css', '.js', '.woff', '.woff2', '.ttf', '.eot', '.otf',
        '.png', '.jpg', '.jpeg', '.gif', '.svg', '.ico', '.webp',
    ];

    // Vite build assets (hashed filenames - safe to cache aggressively)
    if (url.pathname.startsWith('/build/')) {
        return true;
    }

    // Font files from bunny.net
    if (url.hostname.includes('fonts.bunny.net')) {
        return true;
    }

    // Static file extensions
    return staticExtensions.some((ext) => url.pathname.endsWith(ext));
}

/**
 * Check if this is a navigation request (HTML page).
 */
function isNavigationRequest(request) {
    return request.mode === 'navigate' ||
        (request.method === 'GET' && request.headers.get('accept')?.includes('text/html'));
}

/**
 * Check if this is an image request.
 */
function isImageRequest(request) {
    const url = new URL(request.url);
    const imageExtensions = ['.png', '.jpg', '.jpeg', '.gif', '.svg', '.ico', '.webp'];
    return imageExtensions.some((ext) => url.pathname.endsWith(ext));
}
