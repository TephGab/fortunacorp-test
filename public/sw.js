self.addEventListener("install", function (event) {
    event.waitUntil(preLoad());
});

var filesToCache = [
    '/',
    '/offline.html'
];

var preLoad = function () {
    return caches.open("offline").then(function (cache) {
        // caching index and important routes
        return cache.addAll(filesToCache);
    });
};

self.addEventListener("fetch", function (event) {
    event.respondWith(checkResponse(event.request).catch(function () {
        return returnFromCache(event.request);
    }));
    event.waitUntil(addToCache(event.request));
});

var checkResponse = function (request) {
    return new Promise(function (fulfill, reject) {
        fetch(request).then(function (response) {
            if (response.status !== 404) {
                fulfill(response);
            } else {
                reject();
            }
        }, reject);
    });
};

// var addToCache = function (request) {
//     return caches.open("offline").then(function (cache) {
//         return fetch(request).then(function (response) {
//             return cache.put(request, response);
//         });
//     });
// };

var addToCache = function (request) {
    return caches.open("offline").then(function (cache) {
        return fetch(request).then(function (response) {
            // Create a new Request object for cache.put
            var clonedRequest = new Request(request);
            cache.put(clonedRequest, response);
        });
    });
};

var returnFromCache = function (request) {
    return caches.open("offline").then(function (cache) {
        return cache.match(request).then(function (matching) {
            if (!matching || matching.status == 404) {
                return cache.match("offline.html");
            } else {
                return matching;
            }
        });
    });
};

self.addEventListener('push', event => {
    const pushData = event.data.json();

    // Customize notification title and options
    const title = pushData.title || 'New Transaction';
    const options = {
        body: pushData.body || 'New pending transaction!',
        icon: '/img/logo/logo.png',
        sound: '/sounds/new_transaction.mp3'
    };

    // Display the notification and play the sound
    event.waitUntil(
        Promise.all([
            self.registration.showNotification(title, options),
            self.clients.matchAll().then(clients => {
                clients.forEach(client => {
                    client.postMessage({ type: 'playSound' });
                });
            })
        ])
    );
});

self.addEventListener('notificationclick', event => {
    // Handle notification click event if needed
});


// self.addEventListener('push', event => {
//     const pushData = event.data.json();

    // Customize notification title and options
    // const title = pushData.title || 'New Notification';
    // const options = {
    //     body: pushData.body || 'You have a new notification!',
    //     icon: '/path/to/notification-icon.png', // Replace with your notification icon
    // };

    // Display the notification
    //event.waitUntil(
    //    self.registration.showNotification(title, options)
  //  );
//});

//self.addEventListener('notificationclick', event => {
    // Handle notification click event if needed
    // For example, you can open a specific URL when the notification is clicked
//});

// self.addEventListener('push', event => {
//     const options = {
//       body: event.data.text(),
//       icon: 'img/logo/logo.png',
//       vibrate: [100, 50, 100],
//       sound: 'sounds/new_transaction.mp3',
//     };
    
//     event.waitUntil(
//       self.registration.showNotification('New transaction', options)
//     );
// });  

// self.addEventListener('push', (event) => {
//     const data = event.data.json(); // Parse the JSON data from the push notification

//     const options = {
//         body: event.data.text(),
//         icon: '/img/logo/logo.png',
//         badge: '/img/logo/logo.png',
//         vibrate: [100, 50, 100],
//         sound: '/sounds/new_transaction.mp3',
//     };

//     event.waitUntil(
//         self.registration.showNotification(data.title, options)
//     );
// });