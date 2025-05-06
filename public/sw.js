// self.addEventListener('install', (event) => {
//     event.waitUntil(
//         caches.open('nea-mms-cache-v1').then((cache) => {
//             return cache.addAll([
//                 '/',
//                 '/login',
//                 '/assets/img/nea-logo.png',
//                 '/assets/img/nea-logo-192.png',
//                 '/assets/img/nea-logo-512.png'
//             ]);
//         })
//     );
// });

// self.addEventListener('fetch', (event) => {
//     event.respondWith(
//         caches.match(event.request).then((response) => {
//             return response || fetch(event.request);
//         })
//     );
// });

// service-worker.js
const CACHE_NAME = 'your-app-cache-v1';
const urlsToCache = [
  '/',
  '/login',
  '/css/app.css',
  '/js/app.js',
  '/assets/img/nea-logo.png',
  '/assets/img/nea-logo-192.png',
  '/assets/img/nea-logo-512.png',
  '/asets/img/nea-logo-72.png',
  '/assets/img/nea-logo-96.png',
  '/assets/img/nea-logo-128.png',
  '/assets/img/nea-logo-144.png',
  '/assets/img/nea-logo-152.png',
  '/assets/img/nea-logo-167.png',
  '/assets/img/nea-logo-180.png',
  '/assets/img/nea-logo-384.png',

  // Add other assets you want to cache
];

// Install a service worker
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        console.log('Opened cache');
        return cache.addAll(urlsToCache);
      })
  );
});

// Cache and return requests
self.addEventListener('fetch', event => {
  event.respondWith(
    caches.match(event.request)
      .then(response => {
        // Cache hit - return response
        if (response) {
          return response;
        }
        return fetch(event.request).then(
          response => {
            // Check if we received a valid response
            if(!response || response.status !== 200 || response.type !== 'basic') {
              return response;
            }

            // Clone the response
            const responseToCache = response.clone();

            caches.open(CACHE_NAME)
              .then(cache => {
                cache.put(event.request, responseToCache);
              });

            return response;
          }
        );
      })
    );
});

// Update service worker
self.addEventListener('activate', event => {
  const cacheWhitelist = [CACHE_NAME];
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cacheName => {
          if (cacheWhitelist.indexOf(cacheName) === -1) {
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
});