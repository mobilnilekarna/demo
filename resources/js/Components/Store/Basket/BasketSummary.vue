<template>
    <div class="sticky top-4">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Shrnutí objednávky</h2>

            <!-- Počet položek -->
            <div class="flex justify-between items-center mb-4 pb-4 border-b">
                <span class="text-gray-600">Počet položek:</span>
                <span class="font-semibold text-gray-900">{{ totalItems }} ks</span>
            </div>

            <!-- Mezisoučet -->
            <div class="flex justify-between items-center mb-4 pb-4 border-b">
                <span class="text-gray-600">Mezisoučet:</span>
                <span class="font-semibold text-gray-900">{{ formatCurrency(totalPrice) }}</span>
            </div>

            <!-- Doprava -->
            <div class="flex justify-between items-center mb-4 pb-4 border-b">
                <span class="text-gray-600">Doprava:</span>
                <span class="font-semibold text-primary-600">
                    {{ shippingPrice === 0 ? "Zdarma" : formatCurrency(shippingPrice) }}
                </span>
            </div>

            <!-- Celkem -->
            <div class="flex justify-between items-center mb-6">
                <span class="text-lg font-bold text-gray-900">Celkem:</span>
                <span class="text-2xl font-bold text-primary-600">
                    {{ formatCurrency(totalPrice + shippingPrice) }}
                </span>
            </div>

            <!-- Slevový kód -->
            <div class="mb-6 p-4 rounded-lg border border-dashed border-yellow-300 bg-yellow-50/60">
                <div class="flex items-start gap-3 mb-3">
                    <div class="flex items-center justify-center h-9 w-9 rounded-full bg-yellow-100 text-yellow-500">
                        <svg
                            class="w-5 h-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M9 14l6-6m-5.25-.75H5.75A1.75 1.75 0 004 8v3.25c0 .464.184.909.513 1.237l6.5 6.5a1.75 1.75 0 002.474 0l3.763-3.763a1.75 1.75 0 000-2.474l-6.5-6.5A1.75 1.75 0 0013.25 4H16"
                            />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">Využijte slevový kód</p>
                        <p class="text-xs text-gray-500">
                            Pokud máte slevový kód, zadejte jej níže. Slevu uplatníme v dalším kroku objednávky.
                        </p>
                    </div>
                </div>

                <form class="flex flex-col sm:flex-row gap-2" @submit.prevent="handleApplyDiscount">
                    <input
                        v-model="discountCode"
                        type="text"
                        placeholder="Slevový kód"
                        class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                    />
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-semibold text-white bg-warning hover:bg-primary-700 transition-colors whitespace-nowrap"
                    >
                        Uplatnit
                    </button>
                </form>
            </div>

            <!-- CTA tlačítka -->
            <div class="space-y-3">
                <Link
                    href="/checkout"
                    class="block w-full bg-primary-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-primary-700 transition-colors text-center"
                >
                    Pokračovat k objednávce
                </Link>
                <Link
                    href="/products"
                    class="block w-full border-2 border-gray-300 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:border-gray-400 transition-colors text-center"
                >
                    Pokračovat v nákupu
                </Link>
                <button
                    @click="handleClearBasket"
                    class="block w-full text-secondary-600 hover:text-secondary-700 font-semibold py-2"
                >
                    Vyprázdnit košík
                </button>
            </div>

            <!-- Bezpečnostní informace -->
            <div class="mt-6 pt-6 border-t">
                <div class="flex items-center space-x-2 text-sm text-gray-600 mb-2">
                    <svg class="w-5 h-5 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            fill-rule="evenodd"
                            d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                            clip-rule="evenodd"
                        />
                    </svg>
                    <span>Bezpečná platba</span>
                </div>
                <div class="flex items-center space-x-2 text-sm text-gray-600">
                    <svg class="w-5 h-5 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"
                        />
                    </svg>
                    <span>Záruka vrácení peněz</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, ref } from "vue";
import { Link } from "@inertiajs/vue3";
import { formatCurrency } from "@/Utils/format";

const props = defineProps({
    items: {
        type: Array,
        default: () => [],
    },
    totalPrice: {
        type: Number,
        default: 0,
    },
    totalItems: {
        type: Number,
        default: 0,
    },
});

const emit = defineEmits(["clear-basket"]);

const shippingPrice = computed(() => {
    // Doprava zdarma nad 1000 Kč
    return props.totalPrice >= 1000 ? 0 : 99;
});

const handleClearBasket = () => {
    emit("clear-basket");
};

const discountCode = ref("");

const handleApplyDiscount = () => {
    // TODO: Napojit na logiku pro ověření a uplatnění slevového kódu
    if (!discountCode.value) {
        return;
    }

    // Zatím pouze konzole, aby bylo vidět, že handler funguje
    console.log("Uplatněn slevový kód:", discountCode.value);
};
</script>

