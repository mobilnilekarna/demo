<template>
    <div class="relative h-full" ref="dropdownRef" @mouseleave="handleContainerLeave">
        <!-- Basket Button -->
        <Link
            href="/basket"
            @mouseenter="openBasket"
            :class="[
                'relative flex items-center justify-center h-full border-2 border-secondary-600 text-secondary-600 rounded-lg hover:bg-secondary-50 transition-colors',
                variant === 'icon' ? 'w-10 h-10 p-1.5 border border-gray-300 hover:bg-gray-50 rounded-md' : 'px-4 space-x-2'
            ]"
        >
            <svg
                :class="variant === 'icon' ? 'w-7 h-7' : 'w-5 h-5'"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    :stroke-width="variant === 'icon' ? '2.5' : '2'"
                    d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m0 0h8M17 18a2 2 0 100 4 2 2 0 000-4zM9 18a2 2 0 100 4 2 2 0 000-4z"
                ></path>
            </svg>
            <span v-if="variant !== 'icon'" class="text-sm font-medium">Košík</span>
            <!-- Basket Badge -->
            <span
                v-if="basketItems.length > 0"
                :class="[
                    'absolute bg-secondary-500 text-white rounded-full flex items-center justify-center',
                    variant === 'icon' ? 'text-[10px] h-4 w-4 -top-0.5 -right-0.5' : 'text-xs h-5 w-5 -top-2 -right-2'
                ]"
            >
                {{ totalItems }}
            </span>
        </Link>

        <!-- Dropdown -->
        <Transition
            enter-active-class="transition-all duration-300 ease-out"
            enter-from-class="opacity-0 transform -translate-y-2 scale-95"
            enter-to-class="opacity-100 transform translate-y-0 scale-100"
            leave-active-class="transition-all duration-200 ease-in"
            leave-from-class="opacity-100 transform translate-y-0 scale-100"
            leave-to-class="opacity-0 transform -translate-y-2 scale-95"
        >
            <div
                v-if="isOpen"
                class="absolute right-0 top-full w-80 bg-white rounded-lg shadow-lg border z-50 dropdown-container"
                @mouseenter="openBasket"
                @mouseleave="handleContainerLeave"
            >
            <div class="flex flex-col gap-4 p-4 pt-6">
                <!-- Header -->
                <div class="flex items-center justify-between">
                    <Link
                        href="/basket"
                        class="text-lg font-semibold text-gray-900 hover:text-primary-600 transition-colors"
                    >
                        Košík ({{ totalItems }})
                    </Link>
                    <button
                        @click="closeBasket"
                        class="text-gray-400 hover:text-gray-600"
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
                                d="M6 18L18 6M6 6l12 12"
                            ></path>
                        </svg>
                    </button>
                </div>

                <div class="">
                    <FreeShippingIndicator
                        :total-price="totalPrice"
                        :free-shipping-threshold="2000"
                    />
                </div>

                <!-- Basket Items -->
                <div
                    v-if="basketItems.length > 0"
                    class="space-y-3 max-h-64 overflow-y-auto"
                >
                    <div
                        v-for="item in basketItems"
                        :key="item.id"
                        class="flex items-center space-x-3 p-2 border rounded-lg"
                    >
                        <!-- Product Image -->
                        <div
                            class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center"
                        >
                            <span class="text-xs text-gray-500">{{}}</span>
                        </div>

                        <!-- Product Info -->
                        <div class="flex-1 min-w-0">
                            <h4
                                class="text-sm font-medium text-gray-900 truncate"
                            >
                                {{ item.name }}
                            </h4>
                            <p class="text-xs text-gray-500">
                                {{ formatCurrency(item.price) }} ×
                                {{ item.quantity }}
                            </p>
                        </div>

                        <!-- Quantity Controls -->
                        <div class="flex items-center space-x-1">
                            <button
                                @click="
                                    updateQuantity(item.id, item.quantity - 1)
                                "
                                class="w-6 h-6 rounded-full bg-gray-200 hover:bg-gray-300 flex items-center justify-center text-xs"
                            >
                                -
                            </button>
                            <span class="text-sm font-medium w-6 text-center">
                                {{ item.quantity }}
                            </span>
                            <button
                                @click="
                                    updateQuantity(item.id, item.quantity + 1)
                                "
                                class="w-6 h-6 rounded-full bg-gray-200 hover:bg-gray-300 flex items-center justify-center text-xs"
                            >
                                +
                            </button>
                        </div>

                        <!-- Remove Button -->
                        <button
                            @click="removeItem(item.id)"
                            class="text-secondary-500 hover:text-secondary-700 p-1"
                        >
                            <svg
                                class="w-4 h-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                ></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Empty Basket -->
                <div v-else class="text-center">
                    <svg
                        class="w-12 h-12 text-gray-400 mx-auto mb-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m0 0h8M17 18a2 2 0 100 4 2 2 0 000-4zM9 18a2 2 0 100 4 2 2 0 000-4z"
                        ></path>
                    </svg>
                    <p class="text-gray-500">Košík je prázdný</p>
                </div>

                <!-- Footer -->
                <div v-if="basketItems.length > 0" class="pt-4 border-t">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-lg font-semibold text-gray-900">
                            Celkem:
                        </span>
                        <span class="text-lg font-bold text-primary-600">
                            {{ formatCurrency(totalPrice) }}
                        </span>
                    </div>

                    <div class="space-y-2">
                        <Link
                            href="/basket"
                            class="block w-full"
                        >
                            <Button
                                variant="primary"
                                class="w-full"
                            >
                                Zobrazit košík
                            </Button>
                        </Link>
                        <Button
                            @click="clearBasket"
                            variant="secondary"
                            class="w-full"
                        >
                            Vymazat košík
                        </Button>
                    </div>
                </div>
            </div>
        </div>
        </Transition>

    </div>
</template>

<script setup>
import { ref } from "vue";
import Button from "../Button.vue";
import { formatCurrency } from "@/Utils/format";
import { useBasket } from "@/Composables/useBasket";
import { Link } from "@inertiajs/vue3";
import FreeShippingIndicator from "@/Components/Store/Basket/FreeShippingIndicator.vue";

defineProps({
    variant: {
        type: String,
        default: 'button',
        validator: (value) => ['button', 'icon'].includes(value)
    }
});

// Use basket composable
const {
    basketItems,
    totalItems,
    totalPrice,
    addToBasket,
    updateQuantity,
    removeFromBasket,
    clearBasket,
} = useBasket();

// Local state
const isOpen = ref(false);
const dropdownRef = ref(null);

// Methods
const openBasket = () => {
    cancelClose();
    isOpen.value = true;
};

const closeBasket = () => {
    isOpen.value = false;
};

let closeTimeout = null;

const handleContainerLeave = () => {
    // Použít timeout, aby se dropdown nezavřel, když se myš přesouvá mezi tlačítkem a dropdownem
    closeTimeout = setTimeout(() => {
        closeBasket();
    }, 150);
};

const cancelClose = () => {
    if (closeTimeout) {
        clearTimeout(closeTimeout);
        closeTimeout = null;
    }
};

const proceedToCheckout = () => {
    console.log("🛒 Proceeding to checkout with items:", basketItems.value);
    // Zde by byla navigace na checkout stránku
    closeBasket();
};

const removeItem = (itemId) => {
    const confirmed = window.confirm(
        "Opravdu chcete odebrat položku z košíku?"
    );
    if (confirmed) {
        removeFromBasket(itemId);
        console.log("🛒 Item removed from basket");
    }
};


// Expose methods for external use
defineExpose({
    addToBasket,
    clearBasket,
});
</script>

<style scoped>
.dropdown-container {
    transform-origin: top right;
}

/* Plynulá animace pro sjíždění dolů */
.v-enter-active {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    transition-delay: 0.05s;
}

.v-leave-active {
    transition: all 0.2s cubic-bezier(0.4, 0, 1, 1);
}

.v-enter-from {
    opacity: 0;
    transform: translateY(-8px) scale(0.95);
}

.v-enter-to {
    opacity: 1;
    transform: translateY(0) scale(1);
}

.v-leave-from {
    opacity: 1;
    transform: translateY(0) scale(1);
}

.v-leave-to {
    opacity: 0;
    transform: translateY(-8px) scale(0.95);
}
</style>
