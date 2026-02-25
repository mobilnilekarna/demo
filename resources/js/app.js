import './bootstrap';
import '../css/app.css';

import { createApp, h, provide, ref, nextTick } from 'vue';
import { createInertiaApp, router } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { VueReCaptcha } from 'vue-recaptcha-v3';
import { useCookieConsent } from './Composables/useCookieConsent';
import { useToast } from './Composables/useToast';
import Toaster from './Components/Toast/Toaster.vue';
import i18n from './i18n';

/**
 * Globální Inertia error handler pro 419 (CSRF token expired).
 * Při expiraci tokenu automaticky získáme nový a zopakujeme request.
 */
let isHandlingCsrfError = false;

router.on('invalid', async (event) => {
    const status = event.detail.response?.status;

    if (status === 419 && !isHandlingCsrfError) {
        event.preventDefault();
        isHandlingCsrfError = true;

        try {
            // Získáme nový CSRF token
            await window.axios.get('/sanctum/csrf-cookie');

            // Zopakujeme původní Inertia request
            // Jednoduchý reload aktuální stránky s novým tokenem
            router.reload();
        } catch (err) {
            console.error('Nepodařilo se obnovit CSRF token:', err);
        } finally {
            isHandlingCsrfError = false;
        }
    }
});

const appName = import.meta.env.VITE_APP_NAME || 'Mobilné lékárna';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const sharedData = ref({
            mode : "light"
        });

        // Nastavení locale z Laravel
        if (props.initialPage.props.locale) {
            i18n.global.locale.value = props.initialPage.props.locale;
        }

        // Vytvoříš instanci aplikace s Toaster
        const app = createApp({
            render: () => h('div', [
                h(App, props),
                h(Toaster)
            ])
        });

        // Nyní voláš 'provide' na instanci aplikace
        // Zde jsou sdílená data
        app.provide('sharedData', sharedData);

        // Poskytni toast globálně
        const toast = useToast();
        app.provide('toast', toast);
        app.config.globalProperties.$toast = toast;

        // ReCAPTCHA v3 – nutné pro kontaktní formulář
        const recaptchaSiteKey = import.meta.env.VITE_GOOGLE_RECAPTCHA_SITE_KEY;
        if (recaptchaSiteKey) {
            app.use(VueReCaptcha, { siteKey: recaptchaSiteKey });
        }

        // Konfigurace aplikace
        app.use(plugin)
           .use(i18n)
           .mount(el);

        // Inicializace CookieConsent - počkáme na načtení DOMu
        const { initCookieConsent } = useCookieConsent();

        // Použijeme nextTick, aby se inicializace spustila až po mountování
        nextTick(() => {
            // Malé zpoždění, aby se zajistilo, že DOM je plně připraven
            setTimeout(() => {
                initCookieConsent();
            }, 100);
        });

        return app;
    },
    progress: {
        color: '#4B5563',
    },
});
