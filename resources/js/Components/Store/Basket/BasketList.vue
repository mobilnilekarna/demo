<template>
    <section class="max-w-7xl mx-auto w-full px-4 lg:px-0 py-6 lg:py-10">

        <!-- Prázdný košík -->
        <EmptyBasket v-if="basketItems.length === 0" />

        <!-- Košík s produkty -->
        <div
            v-else
            class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8 items-start"
        >
            <!-- Seznam produktů -->
            <div class="lg:col-span-2 space-y-4">
                <div class="bg-white shadow-sm border border-gray-100 rounded-xl overflow-hidden">
                    <div class="px-4 lg:px-6 py-3 border-b border-gray-100 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-blue-50 text-blue-600 text-sm font-semibold">
                                {{ totalItems }}
                            </span>
                            <p class="text-sm font-medium text-gray-800">
                                Přehled položek v košíku
                            </p>
                        </div>
                        <p class="text-xs text-gray-400">
                            Úprava množství a odstranění položek
                        </p>
                    </div>

                    <ul class="divide-y divide-gray-100">
                        <li
                            v-for="item in basketItems"
                            :key="item.id"
                            class="bg-white hover:bg-gray-50 transition-colors duration-150"
                        >
                            <BasketItem
                                :item="item"
                                @update-quantity="handleUpdateQuantity"
                                @remove-item="handleRemoveItem"
                            />
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Sumář -->
            <aside class="lg:col-span-1">
                <div class="sticky top-4">
                    <BasketSummary
                        :items="basketItems"
                        :total-price="totalPrice"
                        :total-items="totalItems"
                        @clear-basket="handleClearBasket"
                    />
                </div>
            </aside>
        </div>
    </section>
</template>

<script setup>
import BasketItem from "@/Components/Store/Basket/BasketItem.vue";
import BasketSummary from "@/Components/Store/Basket/BasketSummary.vue";
import EmptyBasket from "@/Components/Store/Basket/EmptyBasket.vue";

const props = defineProps({
    basketItems: {
        type: Array,
        required: true,
    },
    totalPrice: {
        type: Number,
        required: true,
    },
    totalItems: {
        type: Number,
        required: true,
    },
});

const emit = defineEmits(['update-quantity', 'remove-item', 'clear-basket']);

const handleUpdateQuantity = (itemId, quantity) => {
    emit('update-quantity', itemId, quantity);
};

const handleRemoveItem = (itemId) => {
    emit('remove-item', itemId);
};

const handleClearBasket = () => {
    emit('clear-basket');
};
</script>
