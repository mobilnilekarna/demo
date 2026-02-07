<template>
    <AppLayout title="Shrnutí objednávky">
        <CheckoutSteps />
        <div class="py-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Shrnutí objednávky</h1>

            <div v-if="!hasData" class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-8">
                <p class="text-yellow-800">
                    Žádná data nebyla nalezena. Prosím vraťte se na stránku doručení a vyplňte formulář.
                </p>
                <Link
                    href="/checkout"
                    class="mt-4 inline-block px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors"
                >

                    Zpět na doručení
                </Link>
            </div>

            <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Levý sloupec - Sumář údajů -->
                <div class="lg:col-span-2 space-y-6">
                <!-- Doprava a platba -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">
                        Doprava a platba
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">
                                Způsob dopravy
                            </h3>
                            <p class="text-gray-900 font-medium">
                                {{ checkoutData.transport?.name || getShippingLabel(checkoutData.shipping) }}
                            </p>
                            <p v-if="checkoutData.transport?.price" class="text-sm text-primary-600 font-medium mt-1">
                                {{ formatPrice(checkoutData.transport.price) }} CZK
                            </p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">
                                Způsob platby
                            </h3>
                            <p class="text-gray-900 font-medium">
                                {{ checkoutData.payment?.name || getPaymentLabel(checkoutData.payment) }}
                            </p>
                            <p v-if="checkoutData.payment?.final_price || checkoutData.payment?.price" class="text-sm text-primary-600 font-medium mt-1">
                                {{ formatPrice(checkoutData.payment?.final_price || checkoutData.payment?.price || 0) }} CZK
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Základní údaje -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">
                        Základní údaje
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">
                                Jméno a příjmení
                            </h3>
                            <p class="text-gray-900">
                                {{ checkoutData.firstName }} {{ checkoutData.lastName }}
                            </p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">
                                Email
                            </h3>
                            <p class="text-gray-900">{{ checkoutData.email }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">
                                Telefon
                            </h3>
                            <p class="text-gray-900">
                                <span v-if="checkoutData.phonePrefix">{{ checkoutData.phonePrefix }}</span> {{ checkoutData.phone }}
                            </p>
                        </div>
                    </div>
                </div>


                <!-- Fakturační údaje -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">
                        Fakturační údaje
                    </h2>
                    <div class="space-y-2">
                        <p class="text-gray-900">
                            {{ checkoutData.street }}
                        </p>
                        <p class="text-gray-900">
                            {{ checkoutData.zip }} {{ checkoutData.city }}
                        </p>
                        <p class="text-gray-900">
                            {{ getCountryLabel(checkoutData.country) }}
                        </p>
                    </div>
                </div>

                <!-- Firemní údaje -->
                <div v-if="checkoutData.isCompany" class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">
                        Firemní údaje
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">
                                Název firmy
                            </h3>
                            <p class="text-gray-900">{{ checkoutData.companyName }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">
                                IČO
                            </h3>
                            <p class="text-gray-900">{{ checkoutData.companyId }}</p>
                        </div>
                        <div v-if="checkoutData.companyVatId">
                            <h3 class="text-sm font-medium text-gray-500 mb-1">
                                DIČ
                            </h3>
                            <p class="text-gray-900">{{ checkoutData.companyVatId }}</p>
                        </div>
                    </div>
                </div>

                <!-- Dodací údaje -->
                <div v-if="checkoutData.hasDeliveryAddress" class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">
                        Dodací údaje
                    </h2>
                    <div class="space-y-2">
                        <p class="text-gray-900">
                            {{ checkoutData.deliveryFirstName }}
                            {{ checkoutData.deliveryLastName }}
                        </p>
                        <p class="text-gray-900">
                            {{ checkoutData.deliveryStreet }}
                        </p>
                        <p class="text-gray-900">
                            {{ checkoutData.deliveryZip }}
                            {{ checkoutData.deliveryCity }}
                        </p>
                        <p class="text-gray-900">
                            {{ getCountryLabel(checkoutData.deliveryCountry) }}
                        </p>
                    </div>
                </div>

                <!-- Poznámka -->
                <div v-if="checkoutData.note" class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Poznámka</h2>
                    <p class="text-gray-700 whitespace-pre-wrap">
                        {{ checkoutData.note }}
                    </p>
                </div>

                <!-- Tlačítko zpět -->
                <div class="pt-6">
                    <Link
                        href="/checkout"
                        class="inline-block px-6 py-3 border-2 border-gray-300 rounded-lg font-semibold text-gray-700 hover:border-gray-400 transition-colors"
                    >
                        Zpět k úpravám
                    </Link>
                </div>
                </div>

                <!-- Pravý sloupec - Sticky Summary -->
                <div class="lg:col-span-1">
                    <div class="sticky top-4">
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <h2 class="text-xl font-bold text-gray-900 mb-6">Shrnutí objednávky</h2>

                            <!-- Přehled košíku -->
                            <div class="mb-6 space-y-4 max-h-64 overflow-y-auto">
                                <div v-if="basketItemsWithProducts.length === 0" class="text-sm text-gray-500 text-center py-4">
                                    Košík je prázdný
                                </div>
                                <div
                                    v-for="item in basketItemsWithProducts"
                                    :key="item.id"
                                    class="flex gap-3 pb-4 border-b last:border-0"
                                >
                                    <img
                                        :src="item.product.image"
                                        :alt="item.product.name"
                                        class="w-16 h-16 rounded-lg object-cover flex-shrink-0"
                                    />
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-sm font-medium text-gray-900 truncate">
                                            {{ item.product.name }}
                                        </h3>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ item.quantity }}x {{ formatPrice(item.price) }} CZK
                                        </p>
                                    </div>
                                    <div class="text-sm font-semibold text-gray-900">
                                        {{ formatPrice(item.price_total) }} CZK
                                    </div>
                                </div>
                            </div>

                            <!-- Částky -->
                            <div class="space-y-3 mb-6 pb-6 border-b">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Mezisoučet:</span>
                                    <span class="font-semibold text-gray-900">{{ formatPrice(totalPrice) }} CZK</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Doprava:</span>
                                    <span class="font-semibold text-primary-600">
                                        {{ shippingPrice === 0 ? "Zdarma" : formatPrice(shippingPrice) + " CZK" }}
                                    </span>
                                </div>
                                <div class="flex justify-between text-lg pt-2">
                                    <span class="font-bold text-gray-900">Celkem:</span>
                                    <span class="font-bold text-primary-600">
                                        {{ formatPrice(totalPrice + shippingPrice) }} CZK
                                    </span>
                                </div>
                            </div>

                            <!-- Souhlas s obchodními podmínkami -->
                            <div class="mb-6">
                                <div class="flex items-start">
                                    <input
                                        id="termsAccepted"
                                        v-model="termsAccepted"
                                        type="checkbox"
                                        class="mt-1 w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500"
                                        :class="errors.termsAccepted ? 'border-secondary-500' : ''"
                                    />
                                    <label
                                        for="termsAccepted"
                                        class="ml-3 text-sm text-gray-700"
                                    >
                                        Souhlasím s
                                        <a
                                            href="/terms"
                                            target="_blank"
                                            class="text-primary-600 hover:text-primary-700 underline"
                                        >
                                            obchodními podmínkami
                                        </a>
                                        <span class="text-secondary-500">*</span>
                                    </label>
                                </div>
                                <p v-if="errors.termsAccepted" class="mt-2 text-sm text-secondary-600">
                                    {{ errors.termsAccepted }}
                                </p>
                            </div>

                            <!-- Tlačítko odeslat objednávku -->
                            <button
                                @click="handleSubmitOrder"
                                class="w-full px-6 py-3 bg-primary-600 text-white rounded-lg font-semibold hover:bg-primary-700 transition-colors flex items-center justify-center gap-2"
                            >
                                <svg
                                    class="w-5 h-5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M5 13l4 4L19 7"
                                    />
                                </svg>
                                Odeslat objednávku
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import CheckoutSteps from "@/Components/Checkout/CheckoutSteps.vue";
import { Link, router } from "@inertiajs/vue3";
import { useBasket } from "@/Composables/useBasket";
import {
    getShippingLabel,
    getPaymentLabel,
    getCountryLabel,
} from "@/Utils/order-helpers.js";

const props = defineProps({
    products: {
        type: Array,
        default: () => [],
    },
});

const { basketItems, totalPrice, totalItems, isEmpty } = useBasket();

// Kontrola prázdného košíku - přesměrování na košík
watch(
    isEmpty,
    (empty) => {
        if (empty) {
            router.visit("/basket", {
                replace: true,
            });
        }
    },
    { immediate: true }
);

const STORAGE_KEY = "checkout_data";
const checkoutData = ref({});
const hasData = ref(false);
const termsAccepted = ref(false);
const errors = ref({});

// Propojení basketItems s produkty
const basketItemsWithProducts = computed(() => {
    return basketItems.value.map((item) => {
        const product = props.products.find((p) => p.id === item.model_id);
        if (product) {
            return {
                id: item.id,
                product_id: item.model_id,
                product: product,
                quantity: item.quantity,
                price: item.price,
                price_total: item.price * item.quantity,
            };
        }
        return null;
    }).filter(Boolean);
});

const shippingPrice = computed(() => {
    // Výpočet ceny dopravy a platby z checkout dat
    const transportPrice = checkoutData.value.transport?.price || 0;
    const paymentPrice = checkoutData.value.payment?.final_price || checkoutData.value.payment?.price || 0;
    return transportPrice + paymentPrice;
});

const formatPrice = (price) => {
    return new Intl.NumberFormat("cs-CZ").format(price);
};

// Načtení dat z localStorage
onMounted(() => {
    try {
        const stored = localStorage.getItem(STORAGE_KEY);
        if (stored) {
            const data = JSON.parse(stored);
            // Kontrola, zda jsou základní údaje vyplněné
            if (data.firstName && data.lastName && data.email && data.phone) {
                checkoutData.value = data;
                hasData.value = true;
            }
        }
    } catch (error) {
        console.error("Error loading checkout data:", error);
    }
});

// Potvrzení objednávky
const handleSubmitOrder = () => {
    // Validace souhlasu s obchodními podmínkami
    errors.value = {};
    if (!termsAccepted.value) {
        errors.value.termsAccepted = 'Musíte souhlasit s obchodními podmínkami';
        // Scroll na checkbox
        const checkbox = document.getElementById('termsAccepted');
        if (checkbox) {
            checkbox.scrollIntoView({ behavior: "smooth", block: "center" });
            checkbox.focus();
        }
        return;
    }

    // Validace transport a payment objektů
    if (!checkoutData.value.transport || !checkoutData.value.transport.id) {
        errors.value.transport = 'Vyberte způsob dopravy';
        router.visit('/checkout', { replace: true });
        return;
    }

    if (!checkoutData.value.payment || !checkoutData.value.payment.id) {
        errors.value.payment = 'Vyberte způsob platby';
        router.visit('/checkout', { replace: true });
        return;
    }

    router.post('/orders', checkoutData.value, {
        preserveScroll: true,
        onSuccess: () => {
            // Vymazání checkout dat z localStorage po úspěšném odeslání
            localStorage.removeItem(STORAGE_KEY);
        },
        onError: (serverErrors) => {
            console.error('Chyba při vytváření objednávky:', serverErrors);
        }
    });
};
</script>

