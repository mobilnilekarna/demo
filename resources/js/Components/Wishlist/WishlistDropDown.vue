<template>
    <div class="relative h-full">
        <!-- Wishlist Button -->
        <button
            @click="toggleWishlist"
            :class="[
                'relative flex items-center justify-center h-full border-2 border-primary-600 text-primary-600 rounded-lg hover:bg-primary-50 transition-colors',
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
                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"
                ></path>
            </svg>
            <span v-if="variant !== 'icon'" class="text-sm font-medium">Oblíbené</span>
            <!-- Wishlist Badge -->
            <span
                v-if="wishlistItems.length > 0"
                :class="[
                    'absolute bg-primary-500 text-white rounded-full flex items-center justify-center',
                    variant === 'icon' ? 'text-[10px] h-4 w-4 -top-0.5 -right-0.5' : 'text-xs h-5 w-5 -top-2 -right-2'
                ]"
            >
                {{ totalItems }}
            </span>
        </button>

        <!-- Dropdown -->
        <div
            v-if="isOpen"
            class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border z-50"
        >
            <div class="p-4">
                <!-- Header -->
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Oblíbené ({{ totalItems }})
                    </h3>
                    <button
                        @click="toggleWishlist"
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

                <!-- Wishlist Items -->
                <div
                    v-if="wishlistItems.length > 0"
                    class="space-y-3 max-h-64 overflow-y-auto"
                >
                    <div
                        v-for="item in wishlistItems"
                        :key="item.id"
                        class="flex items-center space-x-3 p-2 border rounded-lg hover:bg-gray-50 transition-colors"
                    >
                        <!-- Product Image -->
                        <div
                            v-if="item.image"
                            class="w-12 h-12 bg-gray-200 rounded-lg overflow-hidden flex-shrink-0"
                        >
                            <img
                                :src="item.image"
                                :alt="item.name"
                                class="w-full h-full object-cover"
                            />
                        </div>
                        <div
                            v-else
                            class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center flex-shrink-0"
                        >
                            <svg
                                class="w-6 h-6 text-gray-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                ></path>
                            </svg>
                        </div>

                        <!-- Product Info -->
                        <div class="flex-1 min-w-0">
                            <Link
                                :href="item.slug ? `/product/${item.slug}` : `/products/${item.id}`"
                                @click="toggleWishlist"
                                class="block"
                            >
                                <h4
                                    class="text-sm font-medium text-gray-900 truncate hover:text-primary-600 transition-colors"
                                >
                                    {{ item.name }}
                                </h4>
                            </Link>
                            <p class="text-xs text-gray-500">
                                {{ formatCurrency(item.price) }}
                            </p>
                        </div>

                        <!-- Remove Button -->
                        <button
                            @click="removeItem(item.id)"
                            class="text-primary-500 hover:text-primary-700 p-1 flex-shrink-0"
                            title="Odebrat z oblíbených"
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

                <!-- Empty Wishlist -->
                <div v-else class="text-center py-8">
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
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"
                        ></path>
                    </svg>
                    <p class="text-gray-500">Oblíbené jsou prázdné</p>
                    <p class="text-sm text-gray-400 mt-2">
                        Přidejte produkty kliknutím na srdíčko
                    </p>
                </div>

                <!-- Footer -->
                <div v-if="wishlistItems.length > 0" class="mt-4 pt-4 border-t">
                    <Button
                        @click="clearWishlist"
                        variant="secondary"
                        class="w-full"
                    >
                        Vymazat oblíbené
                    </Button>
                </div>
            </div>
        </div>

        <!-- Backdrop -->
        <div v-if="isOpen" @click="toggleWishlist" class="fixed inset-0 z-40"></div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import Button from "@/Components/Button.vue";
import { formatCurrency } from "@/Utils/format";
import { useWishList } from "@/Composables/useWishList";
import { Link } from "@inertiajs/vue3";

defineProps({
    variant: {
        type: String,
        default: 'button',
        validator: (value) => ['button', 'icon'].includes(value)
    }
});

// Use wishlist composable
const {
    wishlistItems,
    totalItems,
    removeFromWishlist,
    clearWishlist,
} = useWishList();

// Local state
const isOpen = ref(false);

// Methods
const toggleWishlist = () => {
    isOpen.value = !isOpen.value;
};

const removeItem = (itemId) => {
    const confirmed = window.confirm(
        "Opravdu chcete odebrat produkt z oblíbených?"
    );
    if (confirmed) {
        removeFromWishlist(itemId);
        console.log("❤️ Item removed from wishlist");
    }
};
</script>

