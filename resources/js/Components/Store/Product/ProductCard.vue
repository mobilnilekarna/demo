<template>
    <div
        class="card p-0 h-full flex flex-col"
        itemscope
        itemtype="https://schema.org/Product"
    >
        <Link
            v-if="product.slug"
            :href="`/product/${product.slug}`"
            class="block w-full h-60 border border-solid hover:border-primary-600 transition-border duration-200 rounded-lg bg-white mb-4 flex items-center justify-center overflow-hidden hover:opacity-90 transition-opacity relative p-4"
        >
            <!-- Štítky v levém rohu obrázku -->
            <div v-if="product.badges && product.badges.length > 0" class="absolute top-2 left-2 z-10 flex flex-col items-start gap-1">
                <span
                    v-for="badge in product.badges"
                    :key="badge"
                    :class="getBadgeClass(badge)"
                    class="inline-block px-2 py-1 text-xs font-semibold rounded-md shadow-md whitespace-nowrap"
                >
                    {{ badge }}
                </span>
            </div>
            <Image
                loading="lazy"
                :src="product.image"
                :alt="product.name"
                class="w-full h-full object-contain"
                itemprop="image"
            />
        </Link>

        <div class="space-y-2 flex-grow">
            <Link
                v-if="product.slug"
                :href="`/product/${product.slug}`"
                class="text-lg font-semibold text-gray-900 hover:text-primary-600 transition-colors block"
                itemprop="name"
            >
                {{ product.name }} {{ product.id }}
            </Link>
            <h3 v-else class="text-lg font-semibold text-gray-900" itemprop="name">
                {{ product.name }}
            </h3>
            <p class="text-gray-600 text-sm" itemprop="description">
                {{ product.description }}
            </p>
            <div
                itemprop="offers"
                itemscope
                itemtype="https://schema.org/Offer"
                class="flex items-center justify-between"
            >
                <span
                    class="text-xl font-bold text-primary-600"
                    itemprop="price"
                >
                    {{ formatCurrency(product.price) }}
                </span>
                <span class="text-sm text-primary-600">
                    Skladem
                </span>
            </div>
        </div>

        <div class="mt-4 space-y-2 bg-gray-100 p-4 rounded-lg">
            <div v-if="!isInBasketComputed" class="flex items-center space-x-2">
                <label class="text-sm text-gray-600">Množství:</label>
                <div class="flex items-center space-x-1">
                    <button
                        @click="decreaseQuantity"
                        :disabled="quantity <= 1"
                        class="w-8 h-8 rounded-full bg-gray-200 hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center"
                    >
                        -
                    </button>
                    <span class="w-8 text-center font-medium">{{
                        quantity
                    }}</span>
                    <button
                        @click="increaseQuantity"
                        :disabled="quantity >= product.stock"
                        class="w-8 h-8 rounded-full bg-gray-200 hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center"
                    >
                        +
                    </button>
                </div>
            </div>

            <div v-if="!isInBasketComputed" class="flex space-x-2">
                <Button
                    @click="handleAddToBasket"
                    :disabled="product.stock === 0"
                    variant="primary"
                    size="sm"
                    class="flex-1 flex items-center justify-center !bg-primary-600 hover:!bg-primary-700"
                >
                    <svg
                        v-if="!isLoading"
                        class="w-4 h-4 mr-2"
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
                        class="w-4 h-4 mr-2 animate-spin"
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
                <button
                    @click="handleToggleWishlist"
                    :class="[
                        'px-3 py-2 border-2 rounded-lg font-semibold transition-all duration-200 flex items-center justify-center',
                        isInWishlistComputed
                            ? 'border-primary-500 bg-primary-500 text-white hover:bg-primary-600 hover:border-primary-600 shadow-md'
                            : 'border-gray-300 text-gray-600 hover:border-primary-400 hover:text-primary-600 hover:bg-primary-50'
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
            </div>

            <div v-else-if="isInBasketComputed" class="space-y-2">
                <div
                    class="flex items-center justify-between p-2 bg-primary-50 rounded-lg"
                >
                    <span class="text-sm text-primary-700">
                        V košíku: {{ getItemQuantity(product.id) }} ks
                    </span>
                    <div class="flex items-center space-x-1">
                        <button
                            @click="
                                handleUpdateQuantity(
                                    getItemId(product.id),
                                    getItemQuantity(product.id) - 1
                                )
                            "
                            class="w-6 h-6 rounded-full bg-primary-200 hover:bg-primary-300 flex items-center justify-center text-xs"
                        >
                            -
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
                            class="w-6 h-6 rounded-full bg-primary-200 hover:bg-primary-300 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center text-xs"
                        >
                            +
                        </button>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <Button
                        @click="handleRemoveFromBasket"
                        variant="danger"
                        size="sm"
                        class="flex-1 flex items-center justify-center"
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
                    <button
                        @click="handleToggleWishlist"
                        :class="[
                            'px-3 py-2 border-2 rounded-lg font-semibold transition-all duration-200 flex items-center justify-center',
                            isInWishlistComputed
                                ? 'border-primary-500 bg-primary-500 text-white hover:bg-primary-600 hover:border-primary-600 shadow-md'
                                : 'border-gray-300 text-gray-600 hover:border-primary-400 hover:text-primary-600 hover:bg-primary-50'
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
import AddToBasketModal from "@/Components/Store/Product/AddToBasketModal.vue";
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
    };
    return badgeClasses[badge] || 'bg-gray-500 text-white';
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

const handleUpdateQuantity = async (id,newQuantity) => {
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
.card {
    box-shadow: none;
}
</style>
