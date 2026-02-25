<template>
    <div
        class="card-v2 transition-all duration-200 bg-white h-full flex flex-col"
        itemscope
        itemtype="https://schema.org/Product"
    >
        <!-- Product Image Section -->
        <div class="relative w-full bg-gradient-to-br border border-gray-200 to-stone-100 rounded-lg group mb-3 overflow-hidden">
            <!-- Action Icons - Right Side (shown on hover) - Outside Link to prevent navigation -->
            <div class="absolute top-3 right-3 z-30 flex flex-col gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                <!-- Heart/Favorite Icon -->
                <button
                    @click="handleToggleWishlist"
                    :class="[
                        'w-9 h-9 rounded-full bg-white/90 backdrop-blur-sm shadow-md flex items-center justify-center transition-all duration-200 hover:bg-white hover:scale-110',
                        isInWishlistComputed ? 'text-red-500' : 'text-gray-600'
                    ]"
                    :title="isInWishlistComputed ? 'Odebrat z oblíbených' : 'Přidat do oblíbených'"
                >
                    <svg
                        class="w-5 h-5"
                        :fill="isInWishlistComputed ? 'currentColor' : 'none'"
                        :stroke="isInWishlistComputed ? 'currentColor' : 'currentColor'"
                        :stroke-width="isInWishlistComputed ? '0' : '2'"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"
                        />
                    </svg>
                </button>

                <!-- Expand/View Details Icon -->
                <Link
                    v-if="product.slug"
                    :href="`/product/${product.slug}`"
                    class="w-9 h-9 rounded-full bg-white/90 backdrop-blur-sm shadow-md flex items-center justify-center transition-all duration-200 hover:bg-white hover:scale-110 text-gray-600"
                    title="Zobrazit detail"
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
                            d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"
                        />
                    </svg>
                </Link>

                <!-- Shopping Bag/Add to Cart Icon -->
                <button
                    v-if="!isInBasketComputed && product.stock > 0"
                    @click="handleAddToBasket"
                    :disabled="isLoading"
                    class="w-9 h-9 rounded-full bg-white/90 backdrop-blur-sm shadow-md flex items-center justify-center transition-all duration-200 hover:bg-white hover:scale-110 text-gray-600 disabled:opacity-50 disabled:cursor-not-allowed"
                    title="Přidat do košíku"
                >
                    <svg
                        v-if="!isLoading"
                        class="w-5 h-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"
                        />
                    </svg>
                    <svg
                        v-else
                        class="w-5 h-5 animate-spin"
                        fill="none"
                        viewBox="0 0 24 24"
                    >
                        <circle
                            class="opacity-25"
                            cx="12"
                            cy="12"
                            r="10"
                            stroke="currentColor"
                            stroke-width="4"
                        ></circle>
                        <path
                            class="opacity-75"
                            fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                        ></path>
                    </svg>
                </button>
            </div>

            <Link
                v-if="product.slug"
                :href="`/product/${product.slug}`"
                class="block w-full h-64 flex items-center justify-center overflow-hidden relative p-6"
            >
                <!-- Discount Badge - Top Left (only if badge exists) -->
                <div
                    v-if="product.badges && product.badges.length > 0"
                    class="absolute top-3 left-3 z-20"
                >
                    <span
                        v-for="badge in product.badges"
                        :key="badge"
                        :class="getBadgeClass(badge)"
                        class="inline-block px-3 py-1.5 text-xs font-semibold rounded-lg shadow-sm whitespace-nowrap"
                    >
                        {{ badge }}
                    </span>
                </div>

                <Image
                    loading="lazy"
                    :src="product.image"
                    :alt="product.name"
                    class="w-full h-full object-contain transition-transform duration-300 group-hover:scale-105"
                    itemprop="image"
                />
            </Link>
            <div
                v-else
                class="w-full h-64 flex items-center justify-center relative p-6"
            >
                <!-- Discount Badge - Top Left -->
                <div
                    v-if="product.badges && product.badges.length > 0"
                    class="absolute top-3 left-3 z-20"
                >
                    <span
                        v-for="badge in product.badges"
                        :key="badge"
                        :class="getBadgeClass(badge)"
                        class="inline-block px-3 py-1.5 text-xs font-semibold rounded-lg shadow-sm whitespace-nowrap"
                    >
                        {{ badge }}
                    </span>
                </div>

                <Image
                    :src="product.image"
                    :alt="product.name"
                    class="w-full h-full object-contain"
                    itemprop="image"
                />
            </div>
        </div>

        <!-- Product Details Section -->
        <div class="pb-2 space-y-3 flex-grow flex flex-col">
            <!-- Category and Rating Row -->
            <div class="flex items-center justify-between">
                <span class="text-xs text-gray-500 font-medium">
                    {{ product.category?.name || product.category || 'Produkty' }}
                </span>
                <div class="flex items-center gap-1">
                    <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20">
                        <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                    </svg>
                    <span class="text-sm font-semibold text-gray-700">{{ product.rating || '5.0' }}</span>
                </div>
            </div>

            <!-- Product Name -->
            <Link
                v-if="product.slug"
                :href="`/product/${product.slug}`"
                class="text-lg font-bold text-gray-900 hover:text-primary-600 transition-colors block line-clamp-2"
                itemprop="name"
            >
                {{ product.name }}
            </Link>
            <h3 v-else class="text-lg font-bold text-gray-900 line-clamp-2" itemprop="name">
                {{ product.name }}
            </h3>

            <!-- Price Section with Quantity and Actions -->
            <div
                v-if="!isInBasketComputed"
                itemprop="offers"
                itemscope
                itemtype="https://schema.org/Offer"
                class="price-actions-grid"
            >
                <div class="price-area flex flex-col gap-1">
                    <span class="text-xs text-gray-500 font-medium">
                        {{ product.stock > 0 ? 'Skladem' : 'Není skladem' }}
                    </span>
                    <div class="flex items-center gap-2">
                        <span
                            class="text-2xl font-bold text-black"
                            itemprop="price"
                        >
                            {{ formatCurrency(product.price) }}
                        </span>
                        <span
                            v-if="product.original_price && product.original_price > product.price"
                            class="text-sm text-gray-400 line-through"
                        >
                            {{ formatCurrency(product.original_price) }}
                        </span>
                    </div>
                </div>

                <div class="quantity-actions-area flex items-center gap-2 flex-wrap justify-end">
                    <div class="flex items-center gap-1.5">
                        <button
                            @click="decreaseQuantity"
                            :disabled="quantity <= 1"
                            class="w-7 h-7 rounded-full bg-gray-200 hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center text-sm font-medium transition-colors"
                        >
                            −
                        </button>
                        <span class="w-6 text-center font-semibold text-gray-900">{{
                            quantity
                        }}</span>
                        <button
                            @click="increaseQuantity"
                            :disabled="quantity >= product.stock"
                            class="w-7 h-7 rounded-full bg-gray-200 hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center text-sm font-medium transition-colors"
                        >
                            +
                        </button>
                    </div>

                    <Button
                        @click="handleAddToBasket"
                        :disabled="product.stock === 0 || isLoading"
                        variant="primary"
                        size="sm"
                        class="flex items-center justify-center !bg-primary-600 hover:!bg-primary-700 whitespace-nowrap"
                    >
                        <svg
                            v-if="!isLoading"
                            class="w-4 h-4 mr-1.5"
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
                        <svg
                            v-else
                            class="w-4 h-4 mr-1.5 animate-spin"
                            fill="none"
                            viewBox="0 0 24 24"
                        >
                            <circle
                                class="opacity-25"
                                cx="12"
                                cy="12"
                                r="10"
                                stroke="currentColor"
                                stroke-width="4"
                            ></circle>
                            <path
                                class="opacity-75"
                                fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                            ></path>
                        </svg>
                        {{ product.stock === 0 ? "Není skladem" : "Do košíku" }}
                    </Button>
                </div>
            </div>

            <!-- Price Section and In Basket State -->
            <div v-else class="space-y-3">
                <div
                    itemprop="offers"
                    itemscope
                    itemtype="https://schema.org/Offer"
                    class="flex items-center gap-2"
                >
                    <span
                        class="text-2xl font-bold text-amber-500"
                        itemprop="price"
                    >
                        {{ formatCurrency(product.price) }}
                    </span>
                    <span
                        v-if="product.original_price && product.original_price > product.price"
                        class="text-sm text-gray-400 line-through"
                    >
                        {{ formatCurrency(product.original_price) }}
                    </span>
                </div>

                <div class="space-y-2 pt-2 border-t border-gray-100">
                <div
                    class="flex items-center justify-between p-2 bg-primary-50 rounded-lg"
                >
                    <span class="text-sm text-primary-700 font-medium">
                        V košíku: {{ getItemQuantity(product.id) }} ks
                    </span>
                    <div class="flex items-center gap-1">
                        <button
                            @click="
                                handleUpdateQuantity(
                                    getItemId(product.id),
                                    getItemQuantity(product.id) - 1
                                )
                            "
                            class="w-6 h-6 rounded-full bg-primary-200 hover:bg-primary-300 flex items-center justify-center text-xs font-medium transition-colors"
                        >
                            −
                        </button>
                        <button
                            @click="
                                handleUpdateQuantity(
                                    getItemId(product.id),
                                    getItemQuantity(product.id) + 1
                                )
                            "
                            :disabled="
                                getItemQuantity(product.id) >= product.stock
                            "
                            class="w-6 h-6 rounded-full bg-primary-200 hover:bg-primary-300 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center text-xs font-medium transition-colors"
                        >
                            +
                        </button>
                    </div>
                </div>
                <Button
                    @click="handleRemoveFromBasket"
                    variant="danger"
                    size="sm"
                    class="w-full flex items-center justify-center"
                >
                    <svg
                        class="w-4 h-4 mr-2"
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
                    Odebrat z košíku
                </Button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from "vue";
import Button from "@/Components/Button.vue";
import { useBasket } from "@/Composables/useBasket";
import { useWishList } from "@/Composables/useWishList";
import { formatCurrency } from "@/Utils/format";
import { Image } from "@unpic/vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    product: {
        type: Object,
        required: true,
    },
});

const { openModal, isModalOpen, addToBasket, removeFromBasket, updateQuantity, getItemId, getItemQuantity, isInBasket } =
    useBasket();
const { toggleWishlist, isInWishlist } = useWishList();

// Local state
const quantity = ref(1);
const isLoading = ref(false);

// Computed - používáme metodu z useBasket pro reaktivitu
const isInBasketComputed = isInBasket(props.product.id);
const isInWishlistComputed = isInWishlist(props.product.id);

// Methods
const getBadgeClass = (badge) => {
    const badgeClasses = {
        'TIP': 'bg-yellow-500 text-white',
        'Doporučujeme': 'bg-blue-500 text-white',
        'Doprava zdarma': 'bg-primary-500 text-white',
        '50% off': 'bg-green-700 text-white',
        '10% off': 'bg-green-700 text-white',
        'Akce': 'bg-red-500 text-white',
    };
    // Default to green-700 for discount badges
    if (badge && badge.includes('% off')) {
        return 'bg-green-700 text-white';
    }
    return badgeClasses[badge] || 'bg-green-700 text-white';
};

const increaseQuantity = () => {
    if (quantity.value < props.product.stock) {
        quantity.value++;
    }
};

const decreaseQuantity = () => {
    if (quantity.value > 1) {
        quantity.value--;
    }
};

const handleAddToBasket = async () => {
    if (props.product.stock === 0) return;

    isLoading.value = true;

    try {
        await addToBasket(
            {
                id: props.product.id,
                name: props.product.name,
                price: props.product.price,
                quantity: quantity.value,
                image: props.product.image,
            },
            quantity.value
        );

        // Reset quantity
        quantity.value = 1;

        if(!isModalOpen.value)
            openModal();

        console.log("✅ Product added to basket successfully");
    } catch (error) {
        console.error("❌ Error adding product to basket:", error);
    } finally {
        isLoading.value = false;
    }
};

const handleRemoveFromBasket = async () => {
    const basketItemId = getItemId(props.product.id);
    if (basketItemId) {
        await removeFromBasket(basketItemId);
        console.log("✅ Product removed from basket");
    } else {
        console.error("❌ Basket item ID not found for product:", props.product.id);
    }
};

const handleUpdateQuantity = async (id, newQuantity) => {
    if (newQuantity <= 0) {
        await handleRemoveFromBasket();
    } else {
        await updateQuantity(id, newQuantity);
        console.log("✅ Product quantity updated");
    }
};

const handleToggleWishlist = () => {
    toggleWishlist({
        id: props.product.id,
        name: props.product.name,
        price: props.product.price,
        image: props.product.image || '',
        slug: props.product.slug || null,
    });
};
</script>

<style scoped>
.card-v2 {
    box-shadow: none;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.price-actions-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    grid-template-areas: "price-area quantity-actions-area";
    gap: 1rem;
    align-items: center;
    background-color: #f3f4f6;
    border-radius: 0.5rem;
    padding: 0.75rem;
}

.price-area {
    grid-area: price-area;
}

.quantity-actions-area {
    grid-area: quantity-actions-area;
    justify-content: flex-end;
    width: 100%;
}

/* Mobile layout */
@media (max-width: 640px) {
    .price-actions-grid {
        grid-template-columns: 1fr;
        grid-template-areas:
            "price-area"
            "quantity-actions-area";
    }

    .quantity-actions-area {
        justify-content: space-between;
        width: 100%;
    }
}
</style>
