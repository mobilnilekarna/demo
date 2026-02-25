<template>
    <Teleport to="body">
        <!-- Overlay -->
        <Transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="modelValue"
                class="fixed inset-0 bg-black/50 z-40"
                @click="$emit('update:modelValue', false)"
            ></div>
        </Transition>

        <!-- Modal box -->
        <Transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="opacity-0 translate-y-8 scale-95"
            enter-to-class="opacity-100 translate-y-0 scale-100"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="opacity-100 translate-y-0 scale-100"
            leave-to-class="opacity-0 translate-y-8 scale-95"
        >
            <div
                v-if="modelValue"
                class="fixed inset-0 flex items-center justify-center z-50 pointer-events-none overflow-y-auto py-8"
            >
                <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl relative pointer-events-auto my-auto max-h-[90vh] flex flex-col">
                    <!-- Close button -->
                    <button
                        @click="$emit('update:modelValue', false)"
                        class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 z-20"
                    >
                        ✕
                    </button>

                    <!-- Scrollovatelný obsah -->
                    <div class="flex-1 overflow-y-auto p-6">
                        <div class="space-y-6">
                            <!-- Header s checkmarkem -->
                            <div class="flex items-center justify-center space-x-2">
                                <div class="w-10 h-10 rounded-full bg-primary-500 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <h2 class="text-2xl font-bold text-gray-900">Přidáno do košíku</h2>
                            </div>

                            <!-- Informace o přidaném produktu -->
                            <div class="bg-white border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center space-x-4">
                                    <!-- Obrázek produktu -->
                                    <div class="w-20 h-20 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden flex-shrink-0">
                                        <Image
                                            v-if="product.image"
                                            :src="product.image"
                                            :alt="product.name"
                                            class="w-full h-full object-contain"
                                        />
                                    </div>

                                    <!-- Název a cena -->
                                    <div class="flex-grow min-w-0">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ product.name }}</h3>
                                        <p class="text-xl font-bold text-primary-600">{{ formatCurrency(product.price) }}</p>
                                    </div>

                                    <!-- Množství -->
                                    <div class="flex items-center space-x-2 flex-shrink-0">
                                        <button
                                            @click="handleDecreaseQuantity"
                                            :disabled="currentQuantity <= 1"
                                            class="w-8 h-8 rounded-full bg-gray-200 hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center font-semibold"
                                        >
                                            -
                                        </button>
                                        <span class="w-8 text-center font-medium">{{ currentQuantity }}</span>
                                        <button
                                            @click="handleIncreaseQuantity"
                                            :disabled="currentQuantity >= (product.stock || product.stock_quantity || 999)"
                                            class="w-8 h-8 rounded-full bg-gray-200 hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center font-semibold"
                                        >
                                            +
                                        </button>
                                    </div>

                                    <!-- Odstranit -->
                                    <button
                                        @click="handleRemoveItem"
                                        class="w-8 h-8 rounded-full bg-secondary-100 hover:bg-secondary-200 flex items-center justify-center text-secondary-600 flex-shrink-0"
                                        title="Odebrat z košíku"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Indikátor dopravy zdarma -->
                            <FreeShippingIndicator
                                :total-price="totalPrice"
                                :free-shipping-threshold="freeShippingThreshold"
                            />
                        </div>
                    </div>

                    <!-- Footer s tlačítky - fixní na spodku -->
                    <div class="sticky bottom-0 bg-white border-t border-gray-200 p-6 rounded-b-lg">
                        <div class="flex space-x-4 w-full">
                            <button
                                @click="$emit('update:modelValue', false)"
                                class="flex-1 px-6 py-3 rounded-lg bg-secondary-600 text-white font-semibold hover:bg-secondary-700 transition-colors"
                            >
                                POKRAČOVAT V NÁKUPU
                            </button>
                            <Link
                                href="/basket"
                                @click="$emit('update:modelValue', false)"
                                class="flex-1 px-6 py-3 rounded-lg bg-primary-600 text-white font-semibold hover:bg-primary-700 transition-colors text-center"
                            >
                                PŘEJÍT DO KOŠÍKU
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
import { computed, ref, watch } from "vue";
import { Teleport, Transition } from "vue";
import { useBasket } from "@/Composables/useBasket";
import { formatCurrency } from "@/Utils/format";
import { Image } from "@unpic/vue";
import { Link, usePage } from "@inertiajs/vue3";
import axios from "axios";

import ProductListSwiper from "@/Components/Store/Product/ProductListSwiper.vue";
import FreeShippingIndicator from "@/Components/Store/Basket/FreeShippingIndicator.vue";

const page = usePage();
const addToBasketProducts = page.props.addToBasketProducts;

const props = defineProps({
    modelValue: {
        type: Boolean,
        required: true,
    },
    product: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(["update:modelValue", "update-quantity", "remove-item"]);

// Používáme useBasket pouze pro čtení dat
const { basketItems, totalPrice, getItemId, getItemQuantity } = useBasket();

const freeShippingThreshold = 2000;

// Množství produktu v košíku
const currentQuantity = ref(1);

// Inicializace množství z košíku při otevření modalu
watch(() => props.modelValue, (isOpen) => {
    if (isOpen) {
        const quantity = getItemQuantity(props.product.id);
        currentQuantity.value = quantity > 0 ? quantity : 1;
    }
}, { immediate: true });

// Sleduj změny množství produktu v košíku
watch(() => {
    const item = basketItems.value.find(i => i.model_id === props.product.id);
    return item ? item.quantity : 0;
}, (newQuantity) => {
    if (newQuantity > 0 && props.modelValue) {
        currentQuantity.value = newQuantity;
    }
});

// Handlery - pouze emit events, logiku necháme na rodiči
const handleIncreaseQuantity = () => {
    const basketItemId = getItemId(props.product.id);
    if (basketItemId) {
        emit('update-quantity', basketItemId, currentQuantity.value + 1);
    }
};

const handleDecreaseQuantity = () => {
    const basketItemId = getItemId(props.product.id);
    if (basketItemId && currentQuantity.value > 1) {
        emit('update-quantity', basketItemId, currentQuantity.value - 1);
    }
};

const handleRemoveItem = () => {
    const basketItemId = getItemId(props.product.id);
    if (basketItemId) {
        emit('remove-item', basketItemId);
        emit('update:modelValue', false);
    }
};
</script>

