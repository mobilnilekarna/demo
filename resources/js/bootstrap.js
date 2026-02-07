/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.withCredentials = true;
window.axios.defaults.withXSRFToken = true;

/**
 * Axios interceptor pro automatickou obnovu CSRF tokenu při chybě 419.
 * Když session/token vyprší, automaticky získáme nový a zopakujeme request.
 * Uživatel nic nepozná - vše proběhne na pozadí.
 */
let isRefreshingToken = false;
let failedQueue = [];

const processQueue = (error) => {
    failedQueue.forEach(promise => {
        if (error) {
            promise.reject(error);
        } else {
            promise.resolve();
        }
    });
    failedQueue = [];
};

window.axios.interceptors.response.use(
    (response) => response,
    async (error) => {
        const originalRequest = error.config;
        const status = error.response?.status;

        // Pokud je chyba 401 (Unauthorized) - uživatel je odhlášen
        if (status === 401) {
            // Přesměrujeme na přihlašovací stránku
            window.location.replace('/dashboard/login');
            return Promise.reject(error);
        }

        // Pokud je chyba 419 (CSRF token mismatch) a ještě jsme nezkoušeli obnovit
        if (status === 419 && !originalRequest._retry) {
            // Pokud již probíhá obnova tokenu, přidáme request do fronty
            if (isRefreshingToken) {
                return new Promise((resolve, reject) => {
                    failedQueue.push({ resolve, reject });
                }).then(() => {
                    return window.axios(originalRequest);
                });
            }

            originalRequest._retry = true;
            isRefreshingToken = true;

            try {
                // Získáme nový CSRF token
                await window.axios.get('/sanctum/csrf-cookie');
                
                processQueue(null);
                
                // Zopakujeme původní request s novým tokenem
                return window.axios(originalRequest);
            } catch (refreshError) {
                processQueue(refreshError);
                
                // Pokud ani obnova nepomohla, uživatel se musí znovu přihlásit
                // Můžeme přesměrovat na login nebo zobrazit upozornění
                return Promise.reject(refreshError);
            } finally {
                isRefreshingToken = false;
            }
        }

        return Promise.reject(error);
    }
);

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// import Pusher from 'pusher-js';
// window.Pusher = Pusher;

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_PUSHER_APP_KEY,
//     cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
//     wsHost: import.meta.env.VITE_PUSHER_HOST ? import.meta.env.VITE_PUSHER_HOST : `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
//     wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
//     wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
//     forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
//     enabledTransports: ['ws', 'wss'],
// });
