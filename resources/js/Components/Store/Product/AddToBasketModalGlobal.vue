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
                v-if="isModalOpen"
                class="fixed inset-0 bg-black/50 z-40"
                @click="closeModal()"
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
                v-if="isModalOpen"
                class="fixed inset-0 flex items-center justify-center z-50 pointer-events-none overflow-y-auto py-8"
            >
                <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl relative pointer-events-auto my-auto max-h-[90vh] flex flex-col">
                    <!-- Close button -->
                    <button
                        @click="closeModal()"
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
                            <div v-if="basketItem" class="bg-white border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center space-x-4">
                                    <!-- Obrázek produktu -->
                                    <div class="w-20 h-20 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden flex-shrink-0">
                                        <Image
                                            v-if="basketItem.image"
                                            :src="basketItem.image"
                                            :alt="basketItem.name"
                                            class="w-full h-full object-contain"
                                        />
                                    </div>

                                    <!-- Název a cena -->
                                    <div class="flex-grow min-w-0">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ basketItem.name }}</h3>
                                        <p class="text-xl font-bold text-primary-600">{{ formatCurrency(basketItem.price) }}</p>
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
                                            :disabled="currentQuantity >= (basketItem.stock || basketItem.stock_quantity || 999)"
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
                                @click="closeModal()"
                                class="flex-1 px-6 py-3 rounded-lg bg-secondary-600 text-white font-semibold hover:bg-secondary-700 transition-colors"
                            >
                                POKRAČOVAT V NÁKUPU
                            </button>
                            <Link
                                href="/basket"
                                @click="closeModal()"
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


// Používáme useBasket pro práci s košíkem
const {
    isModalOpen,
    closeModal,
    basketItem,
    basketItems,
    totalPrice,
    getItemId,
    getItemQuantity,
    updateQuantity,
    removeFromBasket
} = useBasket();

const page = usePage();

const freeShippingThreshold = 2000;

// Debug: sledujeme otevření modalu
watch(isModalOpen, (isOpen) => {
    if (isOpen) {
        console.log('🎯 Modal otevřen');
        console.log('📦 basketItem:', basketItem.value);
        console.log('🛒 basketItems:', basketItems.value);
        if (basketItem.value) {
            console.log('🔍 Product ID:', basketItem.value.id);
            const itemId = getItemId(basketItem.value.id);
            console.log('🆔 Basket Item ID:', itemId);
            console.log('📊 Množství v košíku:', getItemQuantity(basketItem.value.id));
        }
    }
});

// Množství produktu v košíku - computed pro reaktivní sledování
const currentQuantity = computed(() => {
    if (!basketItem.value || !basketItem.value.id) {
        console.log('⚠️ basketItem není dostupný nebo nemá id');
        return 1;
    }
    const quantity = getItemQuantity(basketItem.value.id);
    console.log(`📊 Aktuální množství pro produkt ${basketItem.value.id}:`, quantity);
    return quantity > 0 ? quantity : 1;
});

// Handlery - přímo voláme funkce z useBasket
const handleIncreaseQuantity = async () => {
    if (!basketItem.value || !basketItem.value.id) {
        console.error('❌ Nelze zvýšit množství - basketItem není dostupný');
        return;
    }
    const basketItemId = getItemId(basketItem.value.id);
    console.log(`➕ Zvyšuji množství pro položku ${basketItemId}, nové množství: ${currentQuantity.value + 1}`);
    if (basketItemId) {
        await updateQuantity(basketItemId, currentQuantity.value + 1);
    }
};

const handleDecreaseQuantity = async () => {
    if (!basketItem.value || !basketItem.value.id) {
        console.error('❌ Nelze snížit množství - basketItem není dostupný');
        return;
    }
    const basketItemId = getItemId(basketItem.value.id);
    console.log(`➖ Snižuji množství pro položku ${basketItemId}, nové množství: ${currentQuantity.value - 1}`);
    if (basketItemId && currentQuantity.value > 1) {
        await updateQuantity(basketItemId, currentQuantity.value - 1);
    }
};

const handleRemoveItem = async () => {
    if (!basketItem.value || !basketItem.value.id) {
        console.error('❌ Nelze odstranit položku - basketItem není dostupný');
        return;
    }
    const basketItemId = getItemId(basketItem.value.id);
    console.log(`🗑️ Odstraňuji položku ${basketItemId} z košíku`);
    if (basketItemId) {
        await removeFromBasket(basketItemId);
        closeModal();
    }
};
</script>

