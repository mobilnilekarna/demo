<template>
    <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
        <!-- Toggle Button -->
        <button
            @click="isOpen = !isOpen"
            class="w-full px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition-colors"
        >
            <div class="flex items-center gap-4">
                <!-- Icon with rounded gray background -->
                <div class="flex-shrink-0 w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
                    <svg
                        class="w-6 h-6 text-gray-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
                        />
                    </svg>
                </div>
                
                <!-- Basket Info -->
                <div class="text-left">
                    <div class="text-sm font-medium text-gray-500">Košík</div>
                    <div class="text-lg font-semibold text-gray-900">
                        {{ totalItems }} {{ itemsLabel }}
                    </div>
                    <div class="text-sm text-primary-600 font-medium">
                        {{ formatCurrency(totalPrice) }}
                    </div>
                </div>
            </div>

            <!-- Toggle Icon -->
            <svg
                :class="[
                    'w-5 h-5 text-gray-400 transition-transform duration-200',
                    isOpen ? 'transform rotate-180' : ''
                ]"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 9l-7 7-7-7"
                />
            </svg>
        </button>

        <!-- Basket Content (Collapsible) -->
        <Transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="opacity-0 max-h-0"
            enter-to-class="opacity-100 max-h-[2000px]"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="opacity-100 max-h-[2000px]"
            leave-to-class="opacity-0 max-h-0"
        >
            <div v-if="isOpen" class="border-t border-gray-200">
                <div class="px-6 py-4 bg-gray-50">
                    <div class="text-sm font-medium text-gray-700 mb-4">
                        Obsah košíku
                    </div>
                    
                    <!-- Basket Items List -->
                    <div class="space-y-3">
                        <div
                            v-for="item in basketItems"
                            :key="item.id"
                            class="bg-white rounded-lg p-4 border border-gray-200 flex items-start gap-4"
                        >
                            <!-- Product Image -->
                            <div class="flex-shrink-0">
                                <img
                                    v-if="item.product?.image"
                                    :src="item.product.image"
                                    :alt="item.product.name"
                                    class="w-16 h-16 object-cover rounded-lg"
                                />
                                <div
                                    v-else
                                    class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center"
                                >
                                    <svg
                                        class="w-8 h-8 text-gray-400"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                        />
                                    </svg>
                                </div>
                            </div>

                            <!-- Product Info -->
                            <div class="flex-1 min-w-0">
                                <h3 class="text-sm font-semibold text-gray-900 truncate">
                                    {{ item.product?.name || 'Produkt' }}
                                </h3>
                                <p
                                    v-if="item.product?.description"
                                    class="text-xs text-gray-500 mt-1 line-clamp-2"
                                >
                                    {{ item.product.description }}
                                </p>
                                <div class="mt-2 flex items-center justify-between">
                                    <div class="text-sm text-gray-600">
                                        Množství: <span class="font-medium">{{ item.quantity }}</span>
                                    </div>
                                    <div class="text-sm font-semibold text-gray-900">
                                        {{ formatCurrency(item.price * item.quantity) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Summary -->
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="text-sm font-medium text-gray-700">
                                Celkem
                            </div>
                            <div class="text-lg font-bold text-primary-600">
                                {{ formatCurrency(totalPrice) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
    </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { useBasket } from "@/Composables/useBasket";
import { usePage } from "@inertiajs/vue3";
import { formatCurrency } from "@/Utils/format";

const { basketItems: rawBasketItems, totalItems, totalPrice } = useBasket();
const page = usePage();

const isOpen = ref(false);

const props = defineProps({
    itemsLabel: {
        type: String,
        default: "kusů",
    },
    products: {
        type: Array,
        default: () => [],
    },
});

// Propojení basketItems s produkty
const basketItems = computed(() => {
    const products = props.products.length > 0 
        ? props.products 
        : (page.props?.products || []);
    
    return rawBasketItems.value.map((item) => {
        const product = products.find((p) => p.id === item.model_id);
        return {
            ...item,
            product: product || null,
        };
    });
});
</script>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

