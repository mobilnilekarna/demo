import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

export function useCheckoutSteps() {
    const page = usePage();
    const url = computed(() => page.url);

    const currentStep = computed(() => {
        const path = url.value;

        if (path.includes('/order/') && path.includes('/confirmation')) {
            return 4; // Potvrzení
        }
        if (path.includes('/checkout/summary')) {
            return 3; // Rekapitulace
        }
        if (path === '/checkout' || path.startsWith('/checkout?')) {
            return 2; // Doprava, platba a kontaktní údaje
        }
        if (path === '/basket' || path.startsWith('/basket?')) {
            return 1; // Nákupní košík
        }

        return 1; // Defaultně košík
    });

    return {
        currentStep,
    };
}

