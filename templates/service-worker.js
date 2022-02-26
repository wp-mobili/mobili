const CACHE_NAME  = 'mobili_offline';
const OFFLINE_URL = 'offline.html';



self.addEventListener('install', function (event) {
    event.waitUntil((async () => {
        const cache = await caches.open(CACHE_NAME);
        await cache.add(new Request(OFFLINE_URL, {cache: 'reload'}));
    })());

    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil((async () => {
        if ('navigationPreload' in self.registration) {
            await self.registration.navigationPreload.enable();
        }
    })());

    self.clients.claim();
});

self.addEventListener('fetch', function (event) {
    if (event.request.mode === 'navigate') {
        event.respondWith((async () => {
            try {
                const preloadResponse = await event.preloadResponse;
                if (preloadResponse) {
                    return preloadResponse;
                }

                return await fetch(event.request);
            } catch (error) {
                const cache = await caches.open(CACHE_NAME);
                return await cache.match(OFFLINE_URL);
            }
        })());
    }
});
