<template>
    <div class="bg-white rounded-lg shadow-md p-6 flex flex-col sm:flex-row gap-4">
        <!-- Obrázek produktu -->
        <Link
            :href="`/product/1`"
            class="flex-shrink-0 w-full sm:w-32 h-32 rounded-lg overflow-hidden bg-gray-100"
        >
            <!-- Obrázek produktu -->
             <!-- <img
                :src="item.product.image"
                :alt="item.product.name"
                class="w-full h-full object-cover hover:opacity-90 transition-opacity"
            />-->
        </Link>

        <!-- Informace o produktu -->
        <div class="flex-1 flex flex-col sm:flex-row justify-between gap-4">
            <div class="flex-1">
                <Link
                    :href="`/product/1`"
                    class="text-lg font-semibold text-gray-900 hover:text-primary-600 transition-colors block mb-2"
                >
                    Název produktu
                </Link>
                <p class="text-sm text-gray-600 mb-2">
                    Popis
                </p>
                <div class="flex items-center space-x-4 text-sm text-gray-500">
                    <span>Skladem: 5 ks</span>
                </div>
            </div>

            <!-- Cena a množství -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                <!-- Množství -->
                <div class="flex items-center space-x-2">
                    <button
                        @click="decreaseQuantity"
                        :disabled="item.quantity <= 1 || isLoading"
                        class="w-8 h-8 rounded-full bg-gray-200 hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center font-semibold"
                    >
                        -
                    </button>
                    <span class="w-12 text-center font-medium">{{
                        item.quantity
                    }}</span>
                    <button
                        @click="increaseQuantity"
                        :disabled="isLoading"
                        class="w-8 h-8 rounded-full bg-gray-200 hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center font-semibold"
                    >
                        +
                    </button>
                </div>

                <!-- Cena -->
                <div class="text-right">
                    <div class="text-xl font-bold text-gray-900">
                        {{ formatCurrency(item.price_total) }}
                    </div>
                    <div class="text-sm text-gray-500">
                        {{ formatCurrency(item.price) }} / ks
                    </div>
                </div>

                <!-- Smazat -->
                <button
                    @click="handleRemove"
                    :disabled="isLoading"
                    class="p-2 text-secondary-600 hover:text-secondary-700 hover:bg-secondary-50 rounded-lg transition-colors disabled:opacity-50"
                    title="Odebrat z košíku"
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
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                        />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";
import { formatCurrency } from "@/Utils/format";

const props = defineProps({
    item: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(["update-quantity", "remove-item"]);

const isLoading = ref(false);

const formatPrice = (price) => {
    return new Intl.NumberFormat("cs-CZ").format(price);
};

const increaseQuantity = () => {
    if (!isLoading.value) {
        isLoading.value = true;
        emit("update-quantity", props.item.id, props.item.quantity + 1);
        setTimeout(() => {
            isLoading.value = false;
        }, 500);
    }
};

const decreaseQuantity = () => {
    if (props.item.quantity > 1 && !isLoading.value) {
        isLoading.value = true;
        emit("update-quantity", props.item.id, props.item.quantity - 1);
        setTimeout(() => {
            isLoading.value = false;
        }, 500);
    }
};

const handleRemove = () => {
    if (!isLoading.value) {
        isLoading.value = true;
        emit("remove-item", props.item.id);
        setTimeout(() => {
            isLoading.value = false;
        }, 500);
    }
};
</script>

