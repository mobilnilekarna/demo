import {
    buildAddToBasketEvent,
    buildRemoveFromBasketEvent,
    buildLoginUserEvent,
    buildRegisterUserEvent,
    buildSearchEvent,
    buildSubscribeEvent
} from '@/Utils/DataLayer/event.js';
import { buildLogoutUserEvent } from '../Utils/DataLayer/event';
/**
 * Composable pro bezpečné odesílání událostí do datové vrstvy.
 * Funguje jako fasáda pro analytické skripty.
 */
export function useAnalytics() {
    const pushToDataLayer = (data) => {
        if (typeof window !== 'undefined' && window.dataLayer) {
            window.dataLayer.push(data);
            console.log('✅ DataLayer event pushed:', data);
        } else {
            console.error('❌ DataLayer is not available.');
        }
    };

    const trackAddToBasket = (product, quantity = 1) => {
        const eventData = buildAddToBasketEvent(product, quantity);
        pushToDataLayer(eventData);
    };

    const trackRemoveFromBasket = (product, quantity = 1) => {
        const eventData = buildRemoveFromBasketEvent(product, quantity);
        pushToDataLayer(eventData);
    };

    const trackLoginUser = (method) => {
        const eventData = buildLoginUserEvent(method);
        pushToDataLayer(eventData);
    }

    const trackRegisterUser = (method) => {
        const eventData = buildRegisterUserEvent(method);
        pushToDataLayer(eventData);
    }

    const trackLogoutUser = (method) => {
        const eventData = buildLogoutUserEvent();
        pushToDataLayer(eventData);
    }

    const trackSearch = (query) => {
        const eventData = buildSearchEvent(query);
        pushToDataLayer(eventData);
    }

    const trackSubscribe = (email) => {
        const eventData = buildSubscribeEvent(email);
        pushToDataLayer(eventData);
    }

    return {
        trackAddToBasket,
        trackRemoveFromBasket,
        trackLoginUser,
        trackRegisterUser,
        trackLogoutUser,
        trackSearch,
        trackSubscribe
    };
}
